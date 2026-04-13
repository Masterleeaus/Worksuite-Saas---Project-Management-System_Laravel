import 'package:get/get.dart';
import 'package:titan_go/utils/core_export.dart';


class BookingDetailsBinding extends Bindings{
  @override
  void dependencies() {
    Get.lazyPut(() => BookingDetailsController(bookingDetailsRepo: BookingDetailsRepo(apiClient: Get.find())));
    Get.lazyPut(() => BookingEditController(bookingDetailsRepo: BookingDetailsRepo(apiClient: Get.find())));
  }
}