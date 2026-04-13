import 'package:flutter/material.dart';
import 'package:get/get.dart';

import 'core/constants/app_constants.dart';
import 'core/network/api_client.dart';
import 'core/services/auth_service.dart';
import 'core/services/location_service.dart';
import 'core/services/notification_service.dart';
import 'core/services/sync_service.dart';
import 'features/auth/view/login_screen.dart';
import 'features/today/view/today_screen.dart';
import 'features/job_detail/view/job_detail_screen.dart';
import 'features/proof_of_service/view/proof_of_service_screen.dart';
import 'features/checklist/view/checklist_screen.dart';
import 'features/site_memory/view/site_memory_screen.dart';
import 'features/issue_report/view/issue_report_screen.dart';

class TitanGoApp extends StatelessWidget {
  const TitanGoApp({super.key});

  @override
  Widget build(BuildContext context) {
    return GetMaterialApp(
      title: AppConstants.appName,
      theme: _buildTheme(),
      initialBinding: _RootBinding(),
      initialRoute: RouteNames.login,
      getPages: _pages(),
      defaultTransition: Transition.cupertino,
      debugShowCheckedModeBanner: false,
    );
  }

  ThemeData _buildTheme() {
    return ThemeData(
      colorScheme: ColorScheme.fromSeed(
        seedColor: const Color(0xFF1A73E8),
        brightness: Brightness.light,
      ),
      useMaterial3: true,
      fontFamily: 'Roboto',
      appBarTheme: const AppBarTheme(
        backgroundColor: Color(0xFF1A73E8),
        foregroundColor: Colors.white,
        elevation: 0,
      ),
      bottomNavigationBarTheme: const BottomNavigationBarThemeData(
        selectedItemColor: Color(0xFF1A73E8),
        unselectedItemColor: Colors.grey,
      ),
    );
  }

  List<GetPage> _pages() => [
        GetPage(
          name: RouteNames.login,
          page: () => const LoginScreen(),
        ),
        GetPage(
          name: RouteNames.today,
          page: () => const TodayScreen(),
        ),
        GetPage(
          name: RouteNames.jobDetail,
          page: () => const JobDetailScreen(),
        ),
        GetPage(
          name: RouteNames.proofOfService,
          page: () => const ProofOfServiceScreen(),
        ),
        GetPage(
          name: RouteNames.checklist,
          page: () => const ChecklistScreen(),
        ),
        GetPage(
          name: RouteNames.siteMemory,
          page: () => const SiteMemoryScreen(),
        ),
        GetPage(
          name: RouteNames.issueReport,
          page: () => const IssueReportScreen(),
        ),
      ];
}

/// Root bindings — services that persist for the entire app lifecycle.
class _RootBinding extends Bindings {
  @override
  void dependencies() {
    Get.put(ApiClient(), permanent: true);
    Get.put(AuthService(), permanent: true);
    Get.put(SyncService(), permanent: true);
    Get.put(LocationService(), permanent: true);
    Get.put(NotificationService(), permanent: true);
  }
}

abstract class RouteNames {
  static const login        = '/login';
  static const today        = '/today';
  static const jobDetail    = '/job-detail';
  static const proofOfService = '/proof-of-service';
  static const checklist    = '/checklist';
  static const siteMemory   = '/site-memory';
  static const issueReport  = '/issue-report';
}
