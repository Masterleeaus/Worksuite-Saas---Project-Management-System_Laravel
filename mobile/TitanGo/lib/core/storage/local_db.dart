import 'package:hive_flutter/hive_flutter.dart';

/// LocalDb — Hive box registry for Titan Go's offline-first storage layer.
///
/// Boxes:
///   sync_queue   — offline operation queue (replayed by SyncService)
///   jobs_cache   — cached job list for offline today screen
///   site_memory  — cached site access notes per location_id
class LocalDb {
  LocalDb._();

  static const boxSyncQueue  = 'sync_queue';
  static const boxJobsCache  = 'jobs_cache';
  static const boxSiteMemory = 'site_memory';

  static Future<void> init() async {
    await Hive.openBox<Map>(boxSyncQueue);
    await Hive.openBox<Map>(boxJobsCache);
    await Hive.openBox<Map>(boxSiteMemory);
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Job cache
  // ──────────────────────────────────────────────────────────────────────────

  static Box<Map> get jobsCache  => Hive.box<Map>(boxJobsCache);
  static Box<Map> get syncQueue  => Hive.box<Map>(boxSyncQueue);
  static Box<Map> get siteMemory => Hive.box<Map>(boxSiteMemory);

  /// Persist today's job list for offline access.
  static Future<void> cacheJobs(List<Map<String, dynamic>> jobs) async {
    await jobsCache.clear();
    for (final job in jobs) {
      await jobsCache.add(job);
    }
  }

  /// Retrieve cached jobs (used when offline).
  static List<Map<dynamic, dynamic>> getCachedJobs() {
    return jobsCache.values.toList();
  }

  /// Cache site memory (access notes) keyed by location_id.
  static Future<void> cacheSiteMemory(int locationId, Map<String, dynamic> data) async {
    await siteMemory.put('loc_$locationId', data);
  }

  /// Retrieve cached site memory for a location.
  static Map<dynamic, dynamic>? getSiteMemory(int locationId) {
    return siteMemory.get('loc_$locationId');
  }
}
