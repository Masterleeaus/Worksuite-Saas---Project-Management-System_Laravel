import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../controller/login_controller.dart';

class LoginScreen extends StatelessWidget {
  const LoginScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final ctrl = Get.put(LoginController());

    return Scaffold(
      backgroundColor: Theme.of(context).colorScheme.surface,
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const SizedBox(height: 48),
              // Logo / app name
              Center(
                child: Column(
                  children: [
                    Icon(Icons.construction_rounded,
                        size: 64,
                        color: Theme.of(context).colorScheme.primary),
                    const SizedBox(height: 12),
                    Text('Titan Go',
                        style: Theme.of(context).textTheme.headlineMedium?.copyWith(
                              fontWeight: FontWeight.bold,
                              color: Theme.of(context).colorScheme.primary,
                            )),
                    const SizedBox(height: 4),
                    Text('Field Worker App',
                        style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                              color: Colors.grey,
                            )),
                  ],
                ),
              ),
              const SizedBox(height: 48),
              // Email
              TextField(
                keyboardType: TextInputType.emailAddress,
                decoration: const InputDecoration(
                  labelText: 'Work Email',
                  prefixIcon: Icon(Icons.email_outlined),
                  border: OutlineInputBorder(),
                ),
                onChanged: (v) => ctrl.email.value = v,
              ),
              const SizedBox(height: 16),
              // Password
              Obx(() => TextField(
                    obscureText: ctrl.obscurePassword.value,
                    decoration: InputDecoration(
                      labelText: 'Password',
                      prefixIcon: const Icon(Icons.lock_outline),
                      border: const OutlineInputBorder(),
                      suffixIcon: IconButton(
                        icon: Icon(ctrl.obscurePassword.value
                            ? Icons.visibility_off
                            : Icons.visibility),
                        onPressed: ctrl.togglePasswordVisibility,
                      ),
                    ),
                    onChanged: (v) => ctrl.password.value = v,
                    onSubmitted: (_) => ctrl.login(),
                  )),
              const SizedBox(height: 32),
              // Login button
              Obx(() => ElevatedButton(
                    onPressed: ctrl.isLoading.value ? null : ctrl.login,
                    style: ElevatedButton.styleFrom(
                      minimumSize: const Size.fromHeight(52),
                      shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8)),
                    ),
                    child: ctrl.isLoading.value
                        ? const SizedBox(
                            height: 24,
                            width: 24,
                            child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: Colors.white),
                          )
                        : const Text('Sign In',
                            style: TextStyle(fontSize: 16)),
                  )),
            ],
          ),
        ),
      ),
    );
  }
}
