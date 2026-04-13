import 'package:get/get.dart';

import '../../../core/services/auth_service.dart';
import '../../../app.dart';

class LoginController extends GetxController {
  final _auth = Get.find<AuthService>();

  final email    = ''.obs;
  final password = ''.obs;
  final isLoading = false.obs;
  final obscurePassword = true.obs;

  void togglePasswordVisibility() {
    obscurePassword.value = !obscurePassword.value;
  }

  Future<void> login() async {
    final emailVal = email.value.trim();
    final passVal  = password.value;

    if (emailVal.isEmpty || passVal.isEmpty) {
      Get.snackbar('Validation', 'Email and password are required.',
          snackPosition: SnackPosition.BOTTOM);
      return;
    }

    isLoading.value = true;
    final ok = await _auth.login(emailVal, passVal);
    isLoading.value = false;

    if (ok) {
      Get.offAllNamed(RouteNames.today);
    }
  }
}
