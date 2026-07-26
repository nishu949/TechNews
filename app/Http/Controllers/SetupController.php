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
        } catch (\Exception $e) {
            $output['tables'] = [];
        }
        
        return response()->json($output);
    }
    
    public function runMigrations()
    {
        $results = [];
        
        try {
            // 1. Clear cache first
            Artisan::call('config:clear');
            $results['config_clear'] = '✅ Done';
            
            // 2. Run migrations
            Artisan::call('migrate --force');
            $results['migrate'] = Artisan::output();
            
            // 3. Get tables after migration
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            $results['tables'] = $tables;
            
            return response()->json([
                'success' => true,
                'message' => 'Migrations ran successfully!',
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
        $results = [];
        
        try {
            // 1. Drop all tables
            Artisan::call('db:wipe --force');
            $results['wipe'] = '✅ Database wiped';
            
            // 2. Run migrations
            Artisan::call('migrate --force');
            $results['migrate'] = Artisan::output();
            
            // 3. Get tables
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            $results['tables'] = $tables;
            
            return response()->json([
                'success' => true,
                'message' => 'Database fixed successfully!',
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
}