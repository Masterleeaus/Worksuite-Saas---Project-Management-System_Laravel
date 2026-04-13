import 'package:titan_go/utils/core_export.dart';
import 'package:get/get.dart';


class BookingDetailsRepo {
  final ApiClient apiClient;

  BookingDetailsRepo({required this.apiClient});

  /// GET /api/fsm/v1/orders/{id}
  Future<Response> getBookingDetails({required String bookingID, required bool isSubBooking}) async {
    return await apiClient.getData('${AppConstants.bookingDetailsUrl}$bookingID');
  }

  /// POST /api/fsm/v1/orders/{id}/stage  — update job stage (replaces status-update)
  Future<Response> changeBookingStatus({
    required String bookingID,
    required String status,
    required String otp,
    List<MultipartBody>? photoEvidence,
    required bool isSubBooking,
  }) async {
    final List<MultipartBody> photos = photoEvidence ?? [];
    if (photos.isNotEmpty) {
      return await apiClient.postMultipartData(
        '${AppConstants.bookingStatusUpdateUrl}/$bookingID/stage',
        {'stage': status, 'otp': otp},
        photos,
        null,
      );
    }
    return await apiClient.postData(
      '${AppConstants.bookingStatusUpdateUrl}/$bookingID/stage',
      {'stage': status, 'otp': otp},
    );
  }

  /// POST /api/fsm/v1/orders/{id}/checkin
  Future<Response> checkIn({required String bookingID, double? lat, double? lng}) async {
    return await apiClient.postData(
      '${AppConstants.checkInUrl}/$bookingID/checkin',
      {
        if (lat != null) 'latitude':  lat,
        if (lng != null) 'longitude': lng,
      },
    );
  }

  /// POST /api/fsm/v1/orders/{id}/checkout
  Future<Response> checkOut({required String bookingID}) async {
    return await apiClient.postData('${AppConstants.checkOutUrl}/$bookingID/checkout', {});
  }

  /// POST /api/fsm/v1/orders/{id}/complete
  Future<Response> completeVisit({required String bookingID}) async {
    return await apiClient.postData('${AppConstants.completeVisitUrl}/$bookingID/complete', {});
  }

  /// POST /api/fsm/v1/orders/{id}/photos — upload a photo for a visit
  Future<Response> uploadPhoto({required String bookingID, required MultipartBody photo, required String type}) async {
    return await apiClient.postMultipartData(
      '${AppConstants.photosUrl}/$bookingID/photos',
      {'type': type},
      [photo],
      null,
    );
  }

  /// POST /api/nexus/v1/jobs/{id}/issues — structured issue report
  Future<Response> reportIssue({
    required String bookingID,
    required String issueType,
    String? description,
  }) async {
    return await apiClient.postData(
      '${AppConstants.jobIssuesUrl}/$bookingID/issues',
      {
        'issue_type':  issueType,
        'description': description ?? '',
      },
    );
  }

  /// GET/POST /api/nexus/v1/jobs/{id}/checklist
  Future<Response> getChecklist({required String bookingID}) async {
    return await apiClient.getData('${AppConstants.checklistUrl}/$bookingID/checklist');
  }

  Future<Response> submitChecklist({required String bookingID, required List<Map<String, dynamic>> items}) async {
    return await apiClient.postData(
      '${AppConstants.checklistUrl}/$bookingID/checklist',
      {'items': items},
    );
  }

  /// POST /api/nexus/v1/jobs/{id}/notes
  Future<Response> addNote({required String bookingID, required String body, String noteType = 'general'}) async {
    return await apiClient.postData(
      '${AppConstants.jobNotesUrl}/$bookingID/notes',
      {'body': body, 'note_type': noteType},
    );
  }

  // ── Kept for compatibility ────────────────────────────────────────────────

  Future<Response> changePaymentStatus(String bookingID, String paymentStatus) async {
    return await apiClient.postData(
        '${AppConstants.paymentStatusUpdate}/$bookingID',
        {'payment_status': paymentStatus, '_method': 'put'});
  }

  Future<Response> getServiceListBasedOnSubcategory(String subCategoryId) async {
    return await apiClient.getData(
        '${AppConstants.serviceListBasedOnSubCategory}?limit=50&offset=1&sub_category_id=$subCategoryId');
  }

  Future<Response> sendBookingOTPNotification(String? bookingId) {
    return apiClient.getData('${AppConstants.bookingOTPNotificationUri}?booking_id=$bookingId');
  }

  Future<Response> getBookingPriceList(String zoneId, String serviceInfo) {
    return apiClient.getData('${AppConstants.getBookingPriceList}?zone_id=$zoneId&service_info=$serviceInfo');
  }

  Future<Response> removeCartServiceFromServer({CartModel? cart, String? bookingId, String? zoneId}) {
    return apiClient.postData(AppConstants.removeCartServiceFromServer, {
      '_method': 'put',
      'booking_id': bookingId,
      'zone_id': zoneId,
      'variant_key': cart?.variantKey,
      'service_id': cart?.serviceId,
    });
  }

  Future<Response> updateBooking({
    required bool isSubBooking,
    String? bookingId,
    String? subBookingId,
    String? zoneId,
    String? paymentStatus,
    String? servicemanId,
    String? bookingStatus,
    String? serviceSchedule,
    String? serviceInfo,
  }) {
    return apiClient.postData(
      isSubBooking ? AppConstants.updateSubBooking : AppConstants.updateRegularBooking,
      {
        '_method': 'put',
        'booking_id': bookingId,
        'booking_repeat_id': subBookingId,
        'zone_id': zoneId,
        'payment_status': paymentStatus,
        'serviceman_id': servicemanId,
        'booking_status': bookingStatus,
        'service_schedule': serviceSchedule,
        'service_info': serviceInfo,
      },
    );
  }
}

