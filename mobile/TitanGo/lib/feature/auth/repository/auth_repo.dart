import 'package:get/get.dart';
import 'package:titan_go/utils/core_export.dart';

class AuthRepo {
  final ApiClient apiClient;
  final SharedPreferences sharedPreferences;
  AuthRepo({required this.apiClient, required this.sharedPreferences});

  /// Login — POSTs to FSMCore worker auth endpoint.
  /// FSMCore accepts email + password; the Titan Go sign-in screen passes
  /// the phone field but the server stores it as email/phone interchangeably.
  Future<Response?> login({required String phone, required String password}) async {
    return await apiClient.postData(AppConstants.loginUrl, {
      'email':    phone,  // FSMCore accepts email or phone
      'password': password,
    });
  }

  /// Register FCM token — PUT /api/nexus/v1/worker/fcm-token
  /// Also subscribes to company and worker FCM topics.
  Future<Response?> updateToken() async {
    String? deviceToken;
    if (GetPlatform.isIOS) {
      FirebaseMessaging.instance.setForegroundNotificationPresentationOptions(
          alert: true, badge: true, sound: true);
      NotificationSettings settings = await FirebaseMessaging.instance.requestPermission(
        alert: true, announcement: false, badge: true, carPlay: false,
        criticalAlert: false, provisional: true, sound: true,
      );
      if (settings.authorizationStatus == AuthorizationStatus.authorized) {
        deviceToken = await _saveDeviceToken();
      }
    } else {
      deviceToken = await _saveDeviceToken();
    }

    // Tenant-scoped FCM topic subscriptions
    FirebaseMessaging.instance.subscribeToTopic(AppConstants.topic);
    final companyId = sharedPreferences.getInt(AppConstants.companyIdKey);
    final workerId  = sharedPreferences.getInt(AppConstants.workerIdKey);
    if (companyId != null) {
      FirebaseMessaging.instance.subscribeToTopic(AppConstants.companyTopic(companyId));
    }
    if (workerId != null) {
      FirebaseMessaging.instance.subscribeToTopic(AppConstants.workerTopic(workerId));
    }

    // PUT /api/nexus/v1/worker/fcm-token  (tenant-scoped — requires X-Company-ID header)
    return await apiClient.putData(AppConstants.tokenUri, {'fcm_token': deviceToken ?? ''});
  }

  Future<String?> _saveDeviceToken() async {
    String? deviceToken = '@';
    if(!GetPlatform.isWeb) {
      try {
        deviceToken = await FirebaseMessaging.instance.getToken();
      }catch(e) {
        if (kDebugMode) {
          print("");
        }
      }
    }
    if (deviceToken != null) {
      if (kDebugMode) {
        print('--------Device Token---------- $deviceToken');
      }
    }
    return deviceToken;
  }

  Future<void> unsubscribeToken() async {
    if(GetPlatform.isAndroid) {
      FirebaseMessaging.instance.unsubscribeFromTopic('${AppConstants.topic}-${Get.find<UserController>().zoneId}');
      apiClient.postData(AppConstants.tokenUri, {"_method": "put", "fcm_token": "@"});
    }
  }

  Future<Response> sendOtpForForgetPassword(String identity, String identityType) async {
    return await apiClient.postData(AppConstants.sendOtpForForgetPassword,
      {
        "identity": identity,
        "identity_type": identityType
      },

    );
  }

  Future<Response> verifyOtpForForgetPassword(String identity, String identityType, String otp) async {
    return await apiClient.postData(AppConstants.verifyOtpForForgetPasswordScreen,
      {
        "identity": identity,
        'otp':otp,
        "identity_type": identityType
      },
    );
  }

  Future<Response> verifyOtpForFirebaseOtpLogin({String? session, String? phone, String? code }) async {
    return await apiClient.postData(AppConstants.firebaseOtpVerify,
      {
        "sessionInfo": session,
        'phoneNumber': phone,
        'code': code,
        "user_type" : "provider-serviceman"
      },
    );
  }

  Future<Response?> resetPassword(String identity, String identityType, String otp, String password, String confirmPassword, int isFirebaseOtp) async {
    return await apiClient.putData(
      AppConstants.resetPasswordUri,
      {
        "_method": "put",
        "identity": identity,
        "identity_type": identityType,
        "otp": otp,
        "password": password,
        "confirm_password": confirmPassword,
        "is_firebase_otp": isFirebaseOtp
      },
    );
  }

  // for  user token
  Future<bool?> saveUserToken(String token) async {
    apiClient.token = token;
    apiClient.updateHeader(token, null, sharedPreferences.getString(AppConstants.languageCode));
    return await sharedPreferences.setString(AppConstants.token, token);
  }

  bool isLoggedIn() {
    return sharedPreferences.containsKey(AppConstants.token);
  }

  bool clearSharedData() {
    if(GetPlatform.isAndroid) {
      FirebaseMessaging.instance.unsubscribeFromTopic(AppConstants.topic);
      FirebaseMessaging.instance.unsubscribeFromTopic('${AppConstants.topic}-${Get.find<UserController>().zoneId}');
      apiClient.postData(AppConstants.tokenUri, {"_method": "put", "fcm_token": "@"});
    }
    sharedPreferences.remove(AppConstants.token);
    
    apiClient.token = null;
    apiClient.updateHeader(null, null,null);
    return true;
  }

  // for  Remember Email
  Future<void> saveUserNumberAndPassword(String number, String password, String countryCode) async {
    try {
      await sharedPreferences.setString(AppConstants.userPassword, password);
      await sharedPreferences.setString(AppConstants.userNumber, number);
      await sharedPreferences.setString(AppConstants.userCountryCode, countryCode);
    } catch (e) {
      rethrow;
    }
  }

  String getUserNumber() {
    return sharedPreferences.getString(AppConstants.userNumber) ?? "";
  }

  String getUserCountryCode() {
    return sharedPreferences.getString(AppConstants.userCountryCode) ?? "";
  }

  String getUserPassword() {
    return sharedPreferences.getString(AppConstants.userPassword) ?? "";
  }

  Future<bool> clearUserNumberAndPassword() async {
    await sharedPreferences.remove(AppConstants.userPassword);
    await sharedPreferences.remove(AppConstants.userCountryCode);
    return await sharedPreferences.remove(AppConstants.userNumber);
  }
}
