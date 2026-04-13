import 'package:titan_go/utils/core_export.dart';
import 'package:titan_go/feature/booking_details/repository/booking_details_repo.dart';
import 'package:get/get.dart';

/// Report Issue screen — logs structured on-site problems.
/// Submits to POST /api/nexus/v1/jobs/{id}/issues via BookingDetailsRepo.
/// Falls back to an offline-queue note when the device is offline.
class ReportIssueScreen extends StatefulWidget {
  /// The FSM order ID this issue is linked to.
  /// Passed via Get.arguments: { 'booking_id': '123' }
  final String? bookingId;
  const ReportIssueScreen({super.key, this.bookingId});

  @override
  State<ReportIssueScreen> createState() => _ReportIssueScreenState();
}

class _ReportIssueScreenState extends State<ReportIssueScreen> {
  String? _selectedIssueType;
  final TextEditingController _notesController = TextEditingController();
  bool _isLoading = false;

  // Map from display strings to server-side issue_type values
  static const List<Map<String, String>> _issueTypes = [
    {'value': 'access_blocked',    'label': 'access_problem'},
    {'value': 'customer_absent',   'label': 'customer_unavailable'},
    {'value': 'damage',            'label': 'damage_found'},
    {'value': 'safety_risk',       'label': 'safety_risk'},
    {'value': 'extra_work',        'label': 'extra_work_requested'},
    {'value': 'equipment_missing', 'label': 'hazard_detected'},
  ];

  @override
  void dispose() {
    _notesController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (_selectedIssueType == null) return;

    final bookingId = widget.bookingId
        ?? (Get.arguments as Map?)?['booking_id']?.toString();

    if (bookingId == null) {
      showCustomSnackBar('no_booking_id'.tr, type: ToasterMessageType.error);
      return;
    }

    setState(() => _isLoading = true);

    try {
      final repo = Get.find<BookingDetailsRepo>();
      final response = await repo.reportIssue(
        bookingID:   bookingId,
        issueType:   _selectedIssueType!,
        description: _notesController.text.trim(),
      );

      if (response.statusCode == 201 || response.statusCode == 200) {
        showCustomSnackBar('report_submitted'.tr, type: ToasterMessageType.success);
        Get.back();
      } else if (response.statusCode == 1 || response.statusCode == 0) {
        // Offline — acknowledge and navigate back (SyncService will retry)
        showCustomSnackBar('report_queued_offline'.tr, type: ToasterMessageType.info);
        Get.back();
      } else {
        showCustomSnackBar(
          response.body?['message']?.toString() ?? response.statusText ?? 'error'.tr,
        );
      }
    } catch (_) {
      showCustomSnackBar('error'.tr);
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).colorScheme.surface,
      appBar: CustomAppBar(title: 'report_issue'.tr),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(Dimensions.paddingSizeDefault),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'issue_type'.tr,
              style: robotoMedium.copyWith(fontSize: Dimensions.fontSizeDefault),
            ),
            const SizedBox(height: Dimensions.paddingSizeSmall),
            Container(
              decoration: BoxDecoration(
                color: Theme.of(context).cardColor,
                borderRadius: BorderRadius.circular(Dimensions.radiusDefault),
              ),
              child: Column(
                children: _issueTypes
                    .map((type) => RadioListTile<String>(
                          title: Text(type['label']!.tr,
                              style: robotoRegular.copyWith(
                                  fontSize: Dimensions.fontSizeDefault)),
                          value: type['value']!,
                          groupValue: _selectedIssueType,
                          activeColor: Theme.of(context).primaryColor,
                          onChanged: (val) => setState(() => _selectedIssueType = val),
                        ))
                    .toList(),
              ),
            ),
            const SizedBox(height: Dimensions.paddingSizeDefault),
            Text(
              'add_notes'.tr,
              style: robotoMedium.copyWith(fontSize: Dimensions.fontSizeDefault),
            ),
            const SizedBox(height: Dimensions.paddingSizeSmall),
            TextFormField(
              controller: _notesController,
              maxLines: 4,
              style: robotoRegular.copyWith(fontSize: Dimensions.fontSizeDefault),
              decoration: InputDecoration(
                hintText: 'write_something'.tr,
                hintStyle: robotoRegular.copyWith(
                    color: Theme.of(context)
                        .textTheme
                        .bodyLarge
                        ?.color
                        ?.withValues(alpha: 0.4)),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(Dimensions.radiusDefault),
                ),
                filled: true,
                fillColor: Theme.of(context).cardColor,
              ),
            ),
            const SizedBox(height: Dimensions.paddingSizeLarge),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: (_selectedIssueType == null || _isLoading) ? null : _submit,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Theme.of(context).primaryColor,
                  disabledBackgroundColor:
                      Theme.of(context).primaryColor.withValues(alpha: 0.4),
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(
                      vertical: Dimensions.paddingSizeDefault),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(Dimensions.radiusDefault),
                  ),
                ),
                child: _isLoading
                    ? const SizedBox(
                        height: 20,
                        width: 20,
                        child: CircularProgressIndicator(
                            strokeWidth: 2, color: Colors.white))
                    : Text('submit'.tr, style: robotoBold),
              ),
            ),
          ],
        ),
      ),
    );
  }
}


  @override
  void dispose() {
    _notesController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).colorScheme.surface,
      appBar: CustomAppBar(title: 'report_issue'.tr),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(Dimensions.paddingSizeDefault),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'issue_type'.tr,
              style: robotoMedium.copyWith(
                  fontSize: Dimensions.fontSizeDefault),
            ),
            const SizedBox(height: Dimensions.paddingSizeSmall),
            Container(
              decoration: BoxDecoration(
                color: Theme.of(context).cardColor,
                borderRadius:
                    BorderRadius.circular(Dimensions.radiusDefault),
              ),
              child: Column(
                children: _issueTypes
                    .map((type) => RadioListTile<String>(
                          title: Text(type.tr,
                              style: robotoRegular.copyWith(
                                  fontSize: Dimensions.fontSizeDefault)),
                          value: type,
                          groupValue: _selectedIssueType,
                          activeColor: Theme.of(context).primaryColor,
                          onChanged: (val) =>
                              setState(() => _selectedIssueType = val),
                        ))
                    .toList(),
              ),
            ),
            const SizedBox(height: Dimensions.paddingSizeDefault),
            Text(
              'add_notes'.tr,
              style: robotoMedium.copyWith(
                  fontSize: Dimensions.fontSizeDefault),
            ),
            const SizedBox(height: Dimensions.paddingSizeSmall),
            TextFormField(
              controller: _notesController,
              maxLines: 4,
              style: robotoRegular.copyWith(
                  fontSize: Dimensions.fontSizeDefault),
              decoration: InputDecoration(
                hintText: 'write_something'.tr,
                hintStyle: robotoRegular.copyWith(
                    color: Theme.of(context)
                        .textTheme
                        .bodyLarge
                        ?.color
                        ?.withValues(alpha: 0.4)),
                border: OutlineInputBorder(
                  borderRadius:
                      BorderRadius.circular(Dimensions.radiusDefault),
                ),
                filled: true,
                fillColor: Theme.of(context).cardColor,
              ),
            ),
            const SizedBox(height: Dimensions.paddingSizeLarge),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: _selectedIssueType == null
                    ? null
                    : () {
                        showCustomSnackBar('report_submitted'.tr,
                            type: ToasterMessageType.success);
                        Get.back();
                      },
                style: ElevatedButton.styleFrom(
                  backgroundColor: Theme.of(context).primaryColor,
                  disabledBackgroundColor:
                      Theme.of(context).primaryColor.withValues(alpha: 0.4),
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(
                      vertical: Dimensions.paddingSizeDefault),
                  shape: RoundedRectangleBorder(
                    borderRadius:
                        BorderRadius.circular(Dimensions.radiusDefault),
                  ),
                ),
                child: Text('submit'.tr, style: robotoBold),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
