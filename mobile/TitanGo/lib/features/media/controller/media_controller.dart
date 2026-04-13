import 'package:dio/dio.dart';
import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:uuid/uuid.dart';

import '../../../core/constants/app_constants.dart';
import '../../../core/network/api_client.dart';
import '../../../core/services/sync_service.dart';

/// MediaController — handles photo + signature uploads for a visit.
/// Used by ProofOfServiceScreen steps 2, 5 (photos) and 5 (signature).
class MediaController extends GetxController {
  final int    orderId;
  final String photoType; // 'before' | 'after' | 'signature' | 'issue'

  MediaController({required this.orderId, required this.photoType});

  final _picker      = ImagePicker();
  final uploadedUrls = RxList<String>();
  final isUploading  = false.obs;

  Future<void> pickAndUpload() async {
    final file = await _picker.pickImage(
      source:       ImageSource.camera,
      imageQuality: AppConstants.imageQuality,
    );
    if (file == null) return;

    isUploading.value = true;

    final formData = FormData.fromMap({
      'file': await MultipartFile.fromFile(
        file.path,
        filename: '${photoType}_${const Uuid().v4()}.jpg',
      ),
      'type':    photoType == 'signature' ? 'signature' : 'photo',
      'caption': photoType,
    });

    final endpoint = AppConstants.photosUri.replaceAll('{id}', '$orderId');
    final res      = await Get.find<ApiClient>().postMultipart(endpoint, formData: formData);

    if (res.isOk && res.body is Map) {
      final url = (res.body as Map)['photo']?['url']?.toString();
      if (url != null) uploadedUrls.add(url);
    } else if (res.statusCode == 0) {
      // Offline — queue for later; add local file path as placeholder
      await Get.find<SyncService>().enqueue(
        type:     'photo',
        clientId: const Uuid().v4(),
        payload:  {
          'fsm_order_id': orderId,
          'type':         photoType == 'signature' ? 'signature' : 'photo',
          'local_path':   file.path,
        },
      );
      uploadedUrls.add(file.path); // Show locally while offline
    }

    isUploading.value = false;
  }
}
