import 'package:firebase_core/firebase_core.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:hive_flutter/hive_flutter.dart';

import 'app.dart';
import 'core/services/notification_service.dart';
import 'core/storage/local_db.dart';

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Initialise local Hive database (offline cache, sync queue)
  await Hive.initFlutter();
  await LocalDb.init();

  // Initialise Firebase for push notifications
  await Firebase.initializeApp();
  await NotificationService.init();

  runApp(const TitanGoApp());
}
