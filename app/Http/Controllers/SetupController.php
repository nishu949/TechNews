<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SetupController extends Controller
{
    public function index()
    {
        $output = [];
        
        // 1. Clear config cache
        try {
            Artisan::call('config:clear');
            $output['config_clear'] = '✅ Config cleared';
        } catch (\Exception $e) {
            $output['config_clear'] = '❌ ' . $e->getMessage();
        }
        
        // 2. Check database connection
        try {
            DB::connection()->getPdo();
            $output['db_status'] = '✅ Connected!';
            $output['db_name'] = DB::connection()->getDatabaseName();
        } catch (\Exception $e) {
            $output['db_status'] = '❌ Failed!';
            $output['db_error'] = $e->getMessage();
        }
        
        // 3. List tables
        try {
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            $output['tables'] = $tables;
            $output['table_count'] = count($tables);
        } catch (\Exception $e) {
            $output['tables'] = [];
            $output['table_count'] = 0;
        }
        
        return response()->json($output);
    }
    
    public function runMigrations()
    {
        try {
            Artisan::call('migrate:fresh --force');
            $output = Artisan::output();
            
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            
            return response()->json([
                'success' => true,
                'message' => 'Migrations ran successfully!',
                'output' => $output,
                'tables' => $tables,
                'table_count' => count($tables)
            ]);
        } catch (\Exception $e) {
            // Try fallback
            try {
                Artisan::call('migrate --force');
                $output = Artisan::output();
                
                $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Migrations ran successfully (fallback)!',
                    'output' => $output,
                    'tables' => $tables,
                    'table_count' => count($tables)
                ]);
            } catch (\Exception $e2) {
                return response()->json([
                    'success' => false,
                    'message' => $e2->getMessage(),
                    'trace' => $e2->getTraceAsString()
                ], 500);
            }
        }
    }
    
    public function createTables()
    {
        $results = [];
        
        try {
            // Get migration files in correct order
            $migrationFiles = [
                '0001_01_01_000000_create_users_table.php',
                '2026_07_17_153438_create_categories_table.php',
                '2026_07_17_153440_create_articles_table.php',
                '2026_07_17_153439_create_tags_table.php',
                '2026_07_17_153442_create_article_tag_table.php',
                '2026_07_17_153441_create_comments_table.php',
                '2026_07_21_145042_add_role_to_users_table.php',
                '2026_07_23_123930_add_views_and_reading_time_to_articles_table.php',
                '2026_07_23_125139_add_parent_id_and_status_to_comments_table.php'
            ];
            
            $results['migrations'] = $migrationFiles;
            
            foreach ($migrationFiles as $file) {
                // Check if file exists
                $fullPath = database_path('migrations/' . $file);
                if (file_exists($fullPath)) {
                    try {
                        Artisan::call('migrate --path=database/migrations/' . $file . ' --force');
                        $results['success'][] = $file;
                    } catch (\Exception $e) {
                        $results['failed'][] = $file . ': ' . $e->getMessage();
                    }
                } else {
                    $results['not_found'][] = $file;
                }
            }
            
            // Get tables after migration
            try {
                $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
                $results['tables'] = $tables;
                $results['table_count'] = count($tables);
            } catch (\Exception $e) {
                $results['tables'] = [];
                $results['table_count'] = 0;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Individual migrations executed!',
                'results' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    public function seedDatabase()
    {
        try {
            Artisan::call('db:seed --force');
            
            return response()->json([
                'success' => true,
                'message' => 'Database seeded successfully!',
                'output' => Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function clearCache()
    {
        try {
            Artisan::call('optimize:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully!',
                'output' => Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function fixDatabase()
    {
        try {
            // Drop all tables manually
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            
            foreach ($tables as $table) {
                if ($table !== 'migrations') {
                    DB::statement('DROP TABLE IF EXISTS "' . $table . '" CASCADE;');
                }
            }
            
            // Drop migrations table
            DB::statement('DROP TABLE IF EXISTS migrations CASCADE;');
            
            // Run migrations
            Artisan::call('migrate --force');
            $output = Artisan::output();
            
            $newTables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            
            return response()->json([
                'success' => true,
                'message' => 'Database fixed!',
                'dropped_tables' => $tables,
                'new_tables' => $newTables,
                'table_count' => count($newTables),
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}