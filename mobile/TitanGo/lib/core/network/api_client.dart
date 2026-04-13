import 'dart:convert';
import 'package:dio/dio.dart';
import 'package:get/get.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:uuid/uuid.dart';

import '../constants/app_constants.dart';
import 'api_checker.dart';

/// Titan Go API client.
///
/// All requests include tenant-safety headers:
///   Authorization: Bearer <token>
///   X-Company-ID:  <company_id>
///   X-Device-ID:   <device_uuid>
///
/// These three headers form the tenant boundary — the server validates
/// that the token owner's company_id matches X-Company-ID.
class ApiClient extends GetxService {
  late Dio _dio;
  late SharedPreferences _prefs;

  String? _token;
  int?    _companyId;
  String? _deviceId;

  @override
  Future<ApiClient> onInit() async {
    _prefs    = await SharedPreferences.getInstance();
    _token    = _prefs.getString(AppConstants.keyToken);
    _companyId = _prefs.getInt(AppConstants.keyCompanyId);
    _deviceId  = _prefs.getString(AppConstants.keyDeviceId) ?? _generateDeviceId();

    _dio = Dio(BaseOptions(
      baseUrl:        AppConstants.baseUrl,
      connectTimeout: const Duration(seconds: 15),
      receiveTimeout: const Duration(seconds: 30),
      headers:        _buildHeaders(),
    ));

    _dio.interceptors.add(InterceptorsWrapper(
      onError: (DioException err, ErrorInterceptorHandler handler) {
        if (err.response?.statusCode == 401) {
          ApiChecker.handleUnauthorized();
        }
        handler.next(err);
      },
    ));

    return this;
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Public API
  // ──────────────────────────────────────────────────────────────────────────

  Future<Response> get(String path, {Map<String, dynamic>? query}) async {
    try {
      final res = await _dio.get(path, queryParameters: query);
      return _wrap(res);
    } on DioException catch (e) {
      return _wrapError(e);
    }
  }

  Future<Response> post(String path, {dynamic body}) async {
    try {
      final res = await _dio.post(path, data: body);
      return _wrap(res);
    } on DioException catch (e) {
      return _wrapError(e);
    }
  }

  Future<Response> put(String path, {dynamic body}) async {
    try {
      final res = await _dio.put(path, data: body);
      return _wrap(res);
    } on DioException catch (e) {
      return _wrapError(e);
    }
  }

  Future<Response> postMultipart(
    String path, {
    required FormData formData,
  }) async {
    try {
      final res = await _dio.post(path, data: formData);
      return _wrap(res);
    } on DioException catch (e) {
      return _wrapError(e);
    }
  }

  /// Update auth credentials after login.
  void updateCredentials({
    required String token,
    required int companyId,
  }) {
    _token    = token;
    _companyId = companyId;
    _dio.options.headers = _buildHeaders();
  }

  /// Clear credentials on logout.
  void clearCredentials() {
    _token    = null;
    _companyId = null;
    _dio.options.headers = _buildHeaders();
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Helpers
  // ──────────────────────────────────────────────────────────────────────────

  Map<String, String> _buildHeaders() => {
        'Content-Type':  'application/json; charset=UTF-8',
        'Accept':        'application/json',
        if (_token    != null) 'Authorization': 'Bearer $_token',
        if (_companyId != null) 'X-Company-ID': '$_companyId',
        'X-Device-ID': _deviceId ?? '',
      };

  String _generateDeviceId() {
    final id = const Uuid().v4();
    _prefs.setString(AppConstants.keyDeviceId, id);
    return id;
  }

  Response _wrap(io.Response res) => Response(
        statusCode: res.statusCode,
        body:       res.data,
        statusText: res.statusMessage,
      );

  Response _wrapError(DioException e) => Response(
        statusCode: e.response?.statusCode ?? 0,
        body:       e.response?.data,
        statusText: e.message,
      );
}

/// Lightweight response wrapper (mirrors GetX Response shape for compatibility).
class Response {
  final int?    statusCode;
  final dynamic body;
  final String? statusText;

  const Response({this.statusCode, this.body, this.statusText});

  bool get isOk => (statusCode ?? 0) >= 200 && (statusCode ?? 0) < 300;
}
