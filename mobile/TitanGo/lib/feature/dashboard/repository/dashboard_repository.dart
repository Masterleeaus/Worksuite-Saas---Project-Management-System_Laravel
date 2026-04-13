import 'package:get/get_connect/http/src/response/response.dart';
import 'package:titan_go/utils/core_export.dart';

class DashboardRepository {
  final ApiClient apiClient;
  DashboardRepository({required this.apiClient});

  /// GET /api/nexus/v1/dashboard
  /// Returns today's visits, next visit preview, open issue count, stage counts.
  Future<Response> getDashboardData() async {
    return await apiClient.getData(AppConstants.dashboardUrl);
  }

  /// GET /api/fsm/v1/orders?sections=booking_stats&stats_type=full_month
  Future<Response> getMonthlyChartData(String year, String month) async {
    return await apiClient.getData(
        '${AppConstants.bookingRequestUrl}?sections=booking_stats&stats_type=full_month&year=$year&month=$month');
  }

  /// GET /api/fsm/v1/orders?sections=booking_stats&stats_type=full_year
  Future<Response> getYearlyChartData(String year) async {
    return await apiClient.getData(
        '${AppConstants.bookingRequestUrl}?sections=booking_stats&stats_type=full_year&year=$year');
  }

  Future<Response> getBookingStatistic() async {
    return await apiClient.getData(AppConstants.bookingStatisticDataUrl);
  }
}
