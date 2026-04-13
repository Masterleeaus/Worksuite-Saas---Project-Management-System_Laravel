import 'package:titan_go/utils/core_export.dart';
import 'package:titan_go/feature/booking_details/repository/booking_details_repo.dart';
import 'package:get/get.dart';

/// Supply Issue reporting screen — logs missing/low supplies.
/// Submits to POST /api/nexus/v1/jobs/{id}/issues with issue_type=equipment_missing.
class SupplyIssueScreen extends StatefulWidget {
  final String? bookingId;
  const SupplyIssueScreen({super.key, this.bookingId});

  @override
  State<SupplyIssueScreen> createState() => _SupplyIssueScreenState();
}

class _SupplyIssueScreenState extends State<SupplyIssueScreen> {
  String? _selectedType;
  final TextEditingController _notesController = TextEditingController();
  bool _isLoading = false;

  static const List<String> _supplyTypes = [
    'low_chemicals',
    'broken_equipment',
    'missing_tools',
    'restock_required',
  ];

  @override
  void dispose() {
    _notesController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (_selectedType == null) return;

    final bookingId = widget.bookingId
        ?? (Get.arguments as Map?)?['booking_id']?.toString();

    if (bookingId == null) {
      showCustomSnackBar('no_booking_id'.tr, type: ToasterMessageType.error);
      return;
    }

    setState(() => _isLoading = true);

    try {
      final repo = Get.find<BookingDetailsRepo>();
      // All supply issues map to server-side 'equipment_missing' type
      final response = await repo.reportIssue(
        bookingID:   bookingId,
        issueType:   'equipment_missing',
        description: '${_selectedType!.replaceAll('_', ' ').toUpperCase()}: ${_notesController.text.trim()}',
      );

      if (response.statusCode == 201 || response.statusCode == 200) {
        showCustomSnackBar('report_submitted'.tr, type: ToasterMessageType.success);
        Get.back();
      } else if (response.statusCode == 1 || response.statusCode == 0) {
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
      appBar: CustomAppBar(title: 'supply_issues'.tr),
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
                children: _supplyTypes
                    .map((type) => RadioListTile<String>(
                          title: Text(type.tr,
                              style: robotoRegular.copyWith(
                                  fontSize: Dimensions.fontSizeDefault)),
                          value: type,
                          groupValue: _selectedType,
                          activeColor: Theme.of(context).primaryColor,
                          onChanged: (val) => setState(() => _selectedType = val),
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
                onPressed: (_selectedType == null || _isLoading) ? null : _submit,
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
