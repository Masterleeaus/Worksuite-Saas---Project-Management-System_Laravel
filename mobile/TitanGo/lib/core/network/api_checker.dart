import 'package:get/get.dart';

import '../services/auth_service.dart';
import '../../app.dart';

/// Centralised HTTP error handling for Titan Go.
///
/// Mirrors the ApiChecker pattern from the Demandium reference app but
/// adapted for Titan Go's auth flow and FSM lifecycle.
class ApiChecker {
  ApiChecker._();

  static void handleUnauthorized() {
    final auth = Get.find<AuthService>();
    auth.clearSession();
    if (Get.currentRoute != RouteNames.login) {
      Get.offAllNamed(RouteNames.login);
    }
  }

  static void handleError(dynamic body, int? statusCode) {
    final message = _extractMessage(body, statusCode);
    Get.snackbar(
      'Error',
      message,
      snackPosition: SnackPosition.BOTTOM,
      duration: const Duration(seconds: 4),
    );
  }

  static String _extractMessage(dynamic body, int? statusCode) {
    if (body is Map) {
      return body['message']?.toString() ??
             body['error']?.toString() ??
             'Request failed ($statusCode)';
    }
    return 'Request failed (${statusCode ?? 'no response'})';
  }
}
