import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../controller/proof_of_service_controller.dart';
import '../../media/controller/media_controller.dart';
import '../../../app.dart';

/// ProofOfServiceScreen — 8-step wizard that drives the worker through
/// the canonical proof-of-service workflow for a Titan Go visit.
class ProofOfServiceScreen extends StatelessWidget {
  const ProofOfServiceScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final args    = Get.arguments as Map<String, dynamic>? ?? {};
    final orderId = (args['id'] as int?) ?? 0;
    final ctrl    = Get.put(
        ProofOfServiceController(orderId: orderId),
        tag: 'pos_$orderId');

    return Scaffold(
      appBar: AppBar(
        title: const Text('Proof of Service'),
        leading: IconButton(
          icon: const Icon(Icons.close),
          onPressed: () => Get.back(),
        ),
      ),
      body: Obx(() {
        final step = ctrl.currentStep.value;
        return Column(
          children: [
            // Step indicator
            _StepProgress(
              steps:       ProofOfServiceController.steps,
              currentStep: step,
              complete:    ctrl.stepComplete,
            ),
            const Divider(height: 1),
            // Step content
            Expanded(
              child: _stepContent(context, ctrl, step, orderId),
            ),
          ],
        );
      }),
    );
  }

  Widget _stepContent(BuildContext ctx, ProofOfServiceController ctrl,
      int step, int orderId) {
    switch (step) {
      case 0:
        return _CheckInStep(ctrl: ctrl);
      case 1:
        return _PhotoStep(
            orderId: orderId, photoType: 'before', label: 'Before Photos', ctrl: ctrl);
      case 2:
        return _ChecklistStep(orderId: orderId, ctrl: ctrl);
      case 3:
        return _NotesStep(orderId: orderId, ctrl: ctrl);
      case 4:
        return _SignatureStep(orderId: orderId, ctrl: ctrl);
      case 5:
        return _PhotoStep(
            orderId: orderId, photoType: 'after', label: 'After Photos', ctrl: ctrl);
      case 6:
        return _CheckOutStep(ctrl: ctrl);
      case 7:
        return _CompleteStep(ctrl: ctrl);
      default:
        return const SizedBox.shrink();
    }
  }
}

// ──────────────────────────────────────────────────────────────────────────
// Step widgets
// ──────────────────────────────────────────────────────────────────────────

class _CheckInStep extends StatelessWidget {
  final ProofOfServiceController ctrl;
  const _CheckInStep({required this.ctrl});

  @override
  Widget build(BuildContext context) => _StepScaffold(
        icon:    Icons.login_rounded,
        title:   'Check In',
        message: 'Confirm your arrival at the site.',
        onPrimary: () async {
          final ok = await ctrl.checkIn();
          if (!ok) return;
        },
        primaryLabel: 'Check In',
        ctrl:         ctrl,
      );
}

class _CheckOutStep extends StatelessWidget {
  final ProofOfServiceController ctrl;
  const _CheckOutStep({required this.ctrl});

  @override
  Widget build(BuildContext context) => _StepScaffold(
        icon:         Icons.logout_rounded,
        title:        'Check Out',
        message:      'Confirm you are leaving the site.',
        onPrimary:    () async => ctrl.checkOut(),
        primaryLabel: 'Check Out',
        ctrl:         ctrl,
      );
}

class _CompleteStep extends StatelessWidget {
  final ProofOfServiceController ctrl;
  const _CompleteStep({required this.ctrl});

  @override
  Widget build(BuildContext context) => _StepScaffold(
        icon:         Icons.check_circle_rounded,
        title:        'Complete Visit',
        message:      'Mark this visit as complete. The dispatcher will be notified.',
        onPrimary:    () async {
          final ok = await ctrl.completeVisit();
          if (ok) Get.offAllNamed(RouteNames.today);
        },
        primaryLabel: 'Complete Visit',
        ctrl:         ctrl,
        primaryColor: Colors.green,
      );
}

class _PhotoStep extends StatelessWidget {
  final int    orderId;
  final String photoType;
  final String label;
  final ProofOfServiceController ctrl;

  const _PhotoStep({
    required this.orderId,
    required this.photoType,
    required this.label,
    required this.ctrl,
  });

  @override
  Widget build(BuildContext context) {
    final mediaCtrl = Get.put(
        MediaController(orderId: orderId, photoType: photoType),
        tag: '${photoType}_$orderId');

    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Text(label,
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          const Text('Add photos to document the site condition.',
              style: TextStyle(color: Colors.grey)),
          const SizedBox(height: 16),
          Obx(() => Wrap(
                spacing: 8,
                runSpacing: 8,
                children: [
                  ...mediaCtrl.uploadedUrls.map((url) => _PhotoThumb(url: url)),
                  _AddPhotoButton(ctrl: mediaCtrl),
                ],
              )),
          const Spacer(),
          ElevatedButton(
            onPressed: () {
              final stepIndex = photoType == 'before' ? 1 : 5;
              ctrl.stepComplete[stepIndex] = true;
              ctrl.advanceStep();
            },
            style: ElevatedButton.styleFrom(minimumSize: const Size.fromHeight(52)),
            child: Text(mediaCtrl.uploadedUrls.isEmpty
                ? 'Skip'
                : 'Continue'),
          ),
        ],
      ),
    );
  }
}

class _ChecklistStep extends StatelessWidget {
  final int    orderId;
  final ProofOfServiceController ctrl;
  const _ChecklistStep({required this.orderId, required this.ctrl});

  @override
  Widget build(BuildContext context) => _StepScaffold(
        icon:    Icons.checklist_rounded,
        title:   'Checklist',
        message: 'Tap to open the checklist for this visit.',
        onPrimary: () {
          Get.toNamed(RouteNames.checklist,
              arguments: {'order_id': orderId});
          ctrl.stepComplete[2] = true;
          ctrl.advanceStep();
        },
        primaryLabel: 'Open Checklist',
        ctrl:         ctrl,
        allowSkip:    true,
      );
}

class _NotesStep extends StatelessWidget {
  final int    orderId;
  final ProofOfServiceController ctrl;
  const _NotesStep({required this.orderId, required this.ctrl});

  @override
  Widget build(BuildContext context) {
    final noteCtrl = TextEditingController();
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          const Text('Notes & Issues',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          TextField(
            controller: noteCtrl,
            maxLines: 5,
            decoration: const InputDecoration(
              hintText: 'Add visit notes, access notes, or anything relevant...',
              border: OutlineInputBorder(),
            ),
          ),
          const SizedBox(height: 16),
          OutlinedButton.icon(
            onPressed: () => Get.toNamed(RouteNames.issueReport,
                arguments: {'order_id': orderId}),
            icon: const Icon(Icons.report_problem_outlined, color: Colors.orange),
            label: const Text('Report an Issue'),
          ),
          const Spacer(),
          ElevatedButton(
            onPressed: () {
              ctrl.stepComplete[3] = true;
              ctrl.advanceStep();
            },
            style: ElevatedButton.styleFrom(minimumSize: const Size.fromHeight(52)),
            child: const Text('Continue'),
          ),
        ],
      ),
    );
  }
}

class _SignatureStep extends StatelessWidget {
  final int    orderId;
  final ProofOfServiceController ctrl;
  const _SignatureStep({required this.orderId, required this.ctrl});

  @override
  Widget build(BuildContext context) => _StepScaffold(
        icon:    Icons.draw_rounded,
        title:   'Signature',
        message: 'Capture customer signature to confirm job completion.',
        onPrimary: () {
          // Navigate to signature capture — implemented in MediaController
          ctrl.stepComplete[4] = true;
          ctrl.advanceStep();
        },
        primaryLabel: 'Capture Signature',
        ctrl:         ctrl,
        allowSkip:    true,
      );
}

// ──────────────────────────────────────────────────────────────────────────
// Shared helpers
// ──────────────────────────────────────────────────────────────────────────

class _StepScaffold extends StatelessWidget {
  final IconData  icon;
  final String    title;
  final String    message;
  final Future<void> Function() onPrimary;
  final String    primaryLabel;
  final ProofOfServiceController ctrl;
  final Color?    primaryColor;
  final bool      allowSkip;

  const _StepScaffold({
    required this.icon,
    required this.title,
    required this.message,
    required this.onPrimary,
    required this.primaryLabel,
    required this.ctrl,
    this.primaryColor,
    this.allowSkip = false,
  });

  @override
  Widget build(BuildContext context) => Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 72, color: primaryColor ?? Theme.of(context).colorScheme.primary),
            const SizedBox(height: 20),
            Text(title,
                style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            Text(message,
                textAlign: TextAlign.center,
                style: const TextStyle(color: Colors.grey, fontSize: 14)),
            const SizedBox(height: 40),
            Obx(() => ElevatedButton(
                  onPressed: ctrl.isLoading.value ? null : onPrimary,
                  style: ElevatedButton.styleFrom(
                    minimumSize: const Size(240, 52),
                    backgroundColor: primaryColor,
                  ),
                  child: ctrl.isLoading.value
                      ? const SizedBox(
                          height: 22, width: 22,
                          child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                      : Text(primaryLabel),
                )),
            if (allowSkip) ...[
              const SizedBox(height: 12),
              TextButton(
                onPressed: ctrl.advanceStep,
                child: const Text('Skip'),
              ),
            ],
          ],
        ),
      );
}

class _PhotoThumb extends StatelessWidget {
  final String url;
  const _PhotoThumb({required this.url});

  @override
  Widget build(BuildContext context) => ClipRRect(
        borderRadius: BorderRadius.circular(8),
        child: Image.network(url, width: 80, height: 80, fit: BoxFit.cover),
      );
}

class _AddPhotoButton extends StatelessWidget {
  final MediaController ctrl;
  const _AddPhotoButton({required this.ctrl});

  @override
  Widget build(BuildContext context) => GestureDetector(
        onTap: ctrl.pickAndUpload,
        child: Container(
          width:  80,
          height: 80,
          decoration: BoxDecoration(
            border:       Border.all(color: Colors.grey.shade400),
            borderRadius: BorderRadius.circular(8),
          ),
          child: const Icon(Icons.add_a_photo_outlined, color: Colors.grey),
        ),
      );
}

class _StepProgress extends StatelessWidget {
  final List<String>   steps;
  final int            currentStep;
  final RxList<bool>   complete;

  const _StepProgress({
    required this.steps,
    required this.currentStep,
    required this.complete,
  });

  @override
  Widget build(BuildContext context) => SingleChildScrollView(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
        child: Obx(() => Row(
              children: List.generate(steps.length, (i) {
                final isDone    = complete[i];
                final isCurrent = i == currentStep;
                return Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    _StepDot(
                        label: steps[i],
                        isDone: isDone,
                        isCurrent: isCurrent),
                    if (i < steps.length - 1)
                      Container(
                        width: 20,
                        height: 2,
                        color: isDone ? Colors.green : Colors.grey.shade300,
                      ),
                  ],
                );
              }),
            )),
      );
}

class _StepDot extends StatelessWidget {
  final String label;
  final bool   isDone;
  final bool   isCurrent;

  const _StepDot(
      {required this.label, required this.isDone, required this.isCurrent});

  @override
  Widget build(BuildContext context) => Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width:  24,
            height: 24,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: isDone
                  ? Colors.green
                  : isCurrent
                      ? Theme.of(context).colorScheme.primary
                      : Colors.grey.shade300,
            ),
            child: isDone
                ? const Icon(Icons.check, size: 14, color: Colors.white)
                : null,
          ),
          const SizedBox(height: 4),
          Text(label,
              style: TextStyle(
                  fontSize: 9,
                  color: isCurrent
                      ? Theme.of(context).colorScheme.primary
                      : Colors.grey)),
        ],
      );
}
