import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../controller/today_controller.dart';
import '../model/today_model.dart';
import '../widget/visit_card.dart';
import '../widget/next_visit_banner.dart';
import '../../quick_actions/view/quick_action_bar.dart';
import '../../../core/services/auth_service.dart';
import '../../../core/services/sync_service.dart';
import '../../../app.dart';

/// TodayScreen — the Titan Go "Today-first" landing screen.
///
/// Displays:
///   - Worker greeting + shift overview
///   - Open issue count badge
///   - Next visit banner (upcoming visit preview)
///   - Today's visit list (ordered by scheduled time)
///   - Offline indicator when disconnected
///   - Quick-action bar at bottom
class TodayScreen extends StatelessWidget {
  const TodayScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final ctrl  = Get.put(TodayController());
    final auth  = Get.find<AuthService>();
    final sync  = Get.find<SyncService>();

    return Scaffold(
      appBar: AppBar(
        title: const Text('Today'),
        actions: [
          // Offline sync queue badge
          Obx(() => sync.pendingCount > 0
              ? Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: Chip(
                    label: Text('${sync.pendingCount} pending',
                        style: const TextStyle(fontSize: 11)),
                    backgroundColor: Colors.orange,
                    labelStyle: const TextStyle(color: Colors.white),
                  ),
                )
              : const SizedBox.shrink()),
          // Logout
          PopupMenuButton(
            itemBuilder: (_) => [
              const PopupMenuItem(value: 'logout', child: Text('Sign Out')),
            ],
            onSelected: (v) {
              if (v == 'logout') auth.logout();
            },
          ),
        ],
      ),
      body: Obx(() {
        if (ctrl.isLoading.value) {
          return const Center(child: CircularProgressIndicator());
        }

        final model = ctrl.todayModel.value;

        return RefreshIndicator(
          onRefresh: ctrl.refresh,
          child: CustomScrollView(
            slivers: [
              // Offline banner
              if (ctrl.isOffline.value)
                const SliverToBoxAdapter(
                  child: _OfflineBanner(),
                ),

              // Worker greeting + issue count
              SliverToBoxAdapter(
                child: _GreetingHeader(
                  workerName: auth.workerName ?? 'Worker',
                  openIssues: model?.openIssues ?? 0,
                  visitCount: model?.todayVisits.length ?? 0,
                ),
              ),

              // Next visit preview
              if (model?.nextVisit != null)
                SliverToBoxAdapter(
                  child: NextVisitBanner(visit: model!.nextVisit!),
                ),

              // Today's visit list
              if (model != null && model.todayVisits.isNotEmpty)
                SliverList(
                  delegate: SliverChildBuilderDelegate(
                    (ctx, i) => VisitCard(visit: model.todayVisits[i]),
                    childCount: model.todayVisits.length,
                  ),
                )
              else
                const SliverFillRemaining(
                  child: Center(
                    child: Text('No visits scheduled for today.',
                        style: TextStyle(color: Colors.grey)),
                  ),
                ),

              // Spacing for quick-action bar
              const SliverToBoxAdapter(child: SizedBox(height: 80)),
            ],
          ),
        );
      }),
      bottomNavigationBar: const QuickActionBar(),
    );
  }
}

class _GreetingHeader extends StatelessWidget {
  final String workerName;
  final int    openIssues;
  final int    visitCount;

  const _GreetingHeader({
    required this.workerName,
    required this.openIssues,
    required this.visitCount,
  });

  @override
  Widget build(BuildContext context) {
    final now     = DateTime.now();
    final hour    = now.hour;
    final greeting = hour < 12 ? 'Good morning' : hour < 17 ? 'Good afternoon' : 'Good evening';

    return Container(
      color: Theme.of(context).colorScheme.primary,
      padding: const EdgeInsets.fromLTRB(16, 16, 16, 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('$greeting, $workerName',
              style: const TextStyle(
                  color: Colors.white,
                  fontSize: 20,
                  fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          Row(
            children: [
              _StatChip(
                  icon: Icons.assignment_outlined,
                  label: '$visitCount visits today'),
              if (openIssues > 0) ...[
                const SizedBox(width: 8),
                _StatChip(
                    icon: Icons.warning_amber_rounded,
                    label: '$openIssues open issues',
                    color: Colors.orange.shade300),
              ],
            ],
          ),
        ],
      ),
    );
  }
}

class _StatChip extends StatelessWidget {
  final IconData icon;
  final String   label;
  final Color?   color;

  const _StatChip({required this.icon, required this.label, this.color});

  @override
  Widget build(BuildContext context) => Container(
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
        decoration: BoxDecoration(
          color:        color ?? Colors.white.withOpacity(0.2),
          borderRadius: BorderRadius.circular(16),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, size: 14, color: Colors.white),
            const SizedBox(width: 4),
            Text(label,
                style: const TextStyle(color: Colors.white, fontSize: 12)),
          ],
        ),
      );
}

class _OfflineBanner extends StatelessWidget {
  const _OfflineBanner();

  @override
  Widget build(BuildContext context) => Container(
        color: Colors.orange.shade700,
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        child: const Row(
          children: [
            Icon(Icons.wifi_off, color: Colors.white, size: 16),
            SizedBox(width: 8),
            Text('Offline — showing cached data',
                style: TextStyle(color: Colors.white, fontSize: 13)),
          ],
        ),
      );
}
