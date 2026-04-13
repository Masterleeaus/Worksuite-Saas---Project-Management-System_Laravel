import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';

import '../model/today_model.dart';
import '../../../app.dart';

/// Banner showing the next upcoming visit at the top of the Today screen.
class NextVisitBanner extends StatelessWidget {
  final VisitSummary visit;
  const NextVisitBanner({super.key, required this.visit});

  @override
  Widget build(BuildContext context) {
    final timeStr = visit.scheduledStart != null
        ? DateFormat('EEE h:mm a').format(DateTime.parse(visit.scheduledStart!).toLocal())
        : 'Upcoming';

    return GestureDetector(
      onTap: () => Get.toNamed(RouteNames.jobDetail, arguments: {'id': visit.id}),
      child: Container(
        margin: const EdgeInsets.fromLTRB(12, 12, 12, 0),
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: Theme.of(context).colorScheme.primary.withOpacity(0.08),
          border: Border.all(
              color: Theme.of(context).colorScheme.primary.withOpacity(0.3)),
          borderRadius: BorderRadius.circular(12),
        ),
        child: Row(
          children: [
            const Icon(Icons.arrow_forward_ios, size: 14, color: Colors.grey),
            const SizedBox(width: 8),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('NEXT VISIT',
                      style: TextStyle(
                          fontSize: 10,
                          fontWeight: FontWeight.bold,
                          color: Colors.grey,
                          letterSpacing: 1)),
                  const SizedBox(height: 2),
                  Text(visit.name,
                      style: const TextStyle(
                          fontWeight: FontWeight.w600, fontSize: 14)),
                  if (visit.location?.name != null)
                    Text(visit.location!.name,
                        style:
                            const TextStyle(fontSize: 12, color: Colors.grey)),
                ],
              ),
            ),
            Text(timeStr,
                style: TextStyle(
                    color: Theme.of(context).colorScheme.primary,
                    fontWeight: FontWeight.w600,
                    fontSize: 13)),
          ],
        ),
      ),
    );
  }
}
