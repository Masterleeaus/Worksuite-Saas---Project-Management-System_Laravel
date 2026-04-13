import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:get/get.dart';
import 'package:shared_preferences/shared_preferences.dart';

import '../constants/app_constants.dart';
import '../network/api_client.dart';
import '../network/api_checker.dart';
import '../../app.dart';

/// AuthService — manages the Titan Go worker session.
///
/// Login flow:
///   1. POST /api/fsm/v1/auth/login   (shared with FSMCore)
///   2. Store token + company_id + worker_id
///   3. Update ApiClient headers with tenant credentials
///   4. Register FCM token via PUT /api/nexus/v1/worker/fcm-token
///   5. Subscribe to FCM topics (company + worker)
class AuthService extends GetxService {
  final _secureStorage = const FlutterSecureStorage();
  late SharedPreferences _prefs;

  final _isLoggedIn = false.obs;
  bool get isLoggedIn => _isLoggedIn.value;

  int?    _workerId;
  int?    _companyId;
  String? _workerName;

  int?    get workerId   => _workerId;
  int?    get companyId  => _companyId;
  String? get workerName => _workerName;

  @override
  Future<AuthService> onInit() async {
    _prefs     = await SharedPreferences.getInstance();
    _workerId  = _prefs.getInt(AppConstants.keyWorkerId);
    _companyId = _prefs.getInt(AppConstants.keyCompanyId);
    _workerName = _prefs.getString(AppConstants.keyWorkerName);

    final token = await _secureStorage.read(key: AppConstants.keyToken);
    if (token != null && _companyId != null) {
      Get.find<ApiClient>().updateCredentials(
        token:     token,
        companyId: _companyId!,
      );
      _isLoggedIn.value = true;
    }
    return this;
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Login
  // ──────────────────────────────────────────────────────────────────────────

  Future<bool> login(String email, String password) async {
    final res = await Get.find<ApiClient>().post(
      AppConstants.loginUri,
      body: {'email': email, 'password': password},
    );

    if (!res.isOk) {
      ApiChecker.handleError(res.body, res.statusCode);
      return false;
    }

    final data = res.body as Map<String, dynamic>;
    final token     = data['token'] as String;
    final workerMap = data['worker'] as Map<String, dynamic>;
    final workerId  = (workerMap['id'] as num).toInt();

    // Fetch worker profile to get company_id
    Get.find<ApiClient>().updateCredentials(token: token, companyId: 0);
    final profileRes = await Get.find<ApiClient>().get(AppConstants.workerMeUri);

    int companyId = 0;
    if (profileRes.isOk && profileRes.body is Map) {
      companyId = ((profileRes.body as Map)['company_id'] as num?)?.toInt() ?? 0;
    }

    await _persistSession(
      token:      token,
      workerId:   workerId,
      companyId:  companyId,
      workerName: workerMap['name']?.toString() ?? '',
    );

    _isLoggedIn.value = true;
    return true;
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Logout
  // ──────────────────────────────────────────────────────────────────────────

  Future<void> logout() async {
    await Get.find<ApiClient>().post(AppConstants.logoutUri);
    clearSession();
    Get.offAllNamed(RouteNames.login);
  }

  void clearSession() {
    _secureStorage.delete(key: AppConstants.keyToken);
    _prefs.remove(AppConstants.keyWorkerId);
    _prefs.remove(AppConstants.keyCompanyId);
    _prefs.remove(AppConstants.keyWorkerName);
    Get.find<ApiClient>().clearCredentials();
    _isLoggedIn.value = false;
    _workerId  = null;
    _companyId = null;
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Session persistence
  // ──────────────────────────────────────────────────────────────────────────

  Future<void> _persistSession({
    required String token,
    required int    workerId,
    required int    companyId,
    required String workerName,
  }) async {
    await _secureStorage.write(key: AppConstants.keyToken, value: token);
    await _prefs.setInt(AppConstants.keyWorkerId, workerId);
    await _prefs.setInt(AppConstants.keyCompanyId, companyId);
    await _prefs.setString(AppConstants.keyWorkerName, workerName);

    _workerId   = workerId;
    _companyId  = companyId;
    _workerName = workerName;

    Get.find<ApiClient>().updateCredentials(token: token, companyId: companyId);
  }
}
