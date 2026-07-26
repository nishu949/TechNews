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
        
        // 2. Clear view cache
        try {
            Artisan::call('view:clear');
            $output['view_clear'] = '✅ View cache cleared';
        } catch (\Exception $e) {
            $output['view_clear'] = '❌ ' . $e->getMessage();
        }
        
        // 3. Clear route cache
        try {
            Artisan::call('route:clear');
            $output['route_clear'] = '✅ Route cache cleared';
        } catch (\Exception $e) {
            $output['route_clear'] = '❌ ' . $e->getMessage();
        }
        
        // 4. Check database connection
        try {
            DB::connection()->getPdo();
            $output['db_status'] = '✅ Connected!';
            $output['db_name'] = DB::connection()->getDatabaseName();
        } catch (\Exception $e) {
            $output['db_status'] = '❌ Failed!';
            $output['db_error'] = $e->getMessage();
        }
        
        // 5. Show environment
        $output['env'] = [
            'APP_ENV' => env('APP_ENV'),
            'DB_CONNECTION' => env('DB_CONNECTION'),
            'APP_DEBUG' => env('APP_DEBUG'),
        ];
        
        return response()->json($output);
    }
    
    public function runMigrations()
    {
        try {
            Artisan::call('migrate --force');
            
            return response()->json([
                'success' => true,
                'message' => 'Migrations ran successfully!',
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
}