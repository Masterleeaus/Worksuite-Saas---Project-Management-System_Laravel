import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

import '../model/today_model.dart';
import '../../../app.dart';
import 'package:get/get.dart';

/// Card for a single visit in the Today list.
class VisitCard extends StatelessWidget {
  final VisitSummary visit;
  const VisitCard({super.key, required this.visit});

  @override
  Widget build(BuildContext context) {
    final timeStr = visit.scheduledStart != null
        ? DateFormat('h:mm a').format(DateTime.parse(visit.scheduledStart!).toLocal())
        : '—';

    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      child: InkWell(
        borderRadius: BorderRadius.circular(12),
        onTap: () => Get.toNamed(RouteNames.jobDetail, arguments: {'id': visit.id}),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Row(
            children: [
              // Time column
              SizedBox(
                width: 56,
                child: Column(
                  children: [
                    Text(timeStr.split(' ')[0],
                        style: const TextStyle(
                            fontWeight: FontWeight.bold, fontSize: 16)),
                    Text(timeStr.split(' ').length > 1 ? timeStr.split(' ')[1] : '',
                        style:
                            const TextStyle(fontSize: 11, color: Colors.grey)),
                  ],
                ),
              ),
              const SizedBox(width: 8),
              Container(width: 2, height: 48, color: Colors.grey.shade300),
              const SizedBox(width: 12),
              // Details
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Expanded(
                          child: Text(visit.name,
                              style: const TextStyle(
                                  fontWeight: FontWeight.w600, fontSize: 15)),
                        ),
                        if (visit.isUrgent)
                          const Icon(Icons.priority_high,
                              color: Colors.red, size: 18),
                      ],
                    ),
                    if (visit.location?.fullAddress.isNotEmpty ?? false) ...[
                      const SizedBox(height: 4),
                      Text(visit.location!.fullAddress,
                          style: const TextStyle(
                              fontSize: 12, color: Colors.grey),
                          overflow: TextOverflow.ellipsis),
                    ],
                    const SizedBox(height: 6),
                    _StageBadge(stage: visit.stage, isComplete: visit.isComplete),
                  ],
                ),
              ),
              const Icon(Icons.chevron_right, color: Colors.grey),
            ],
          ),
        ),
      ),
    );
  }
}

class _StageBadge extends StatelessWidget {
  final String? stage;
  final bool    isComplete;

  const _StageBadge({this.stage, required this.isComplete});

  @override
  Widget build(BuildContext context) {
    if (stage == null) return const SizedBox.shrink();

    final color = isComplete
        ? Colors.green
        : stage!.toLowerCase().contains('progress')
            ? Colors.blue
            : stage!.toLowerCase().contains('route')
                ? Colors.orange
                : Colors.grey;

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
      decoration: BoxDecoration(
        color:        color.withOpacity(0.1),
        border:       Border.all(color: color.withOpacity(0.5)),
        borderRadius: BorderRadius.circular(4),
      ),
      child: Text(stage!,
          style: TextStyle(fontSize: 11, color: color, fontWeight: FontWeight.w500)),
    );
  }
}
