<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrationController extends Controller
{
    public function runMigrations()
    {
        // Check if user is admin (you can remove this check later)
        // For now, we'll allow anyone to run migrations (for setup only)
        
        try {
            // Test database connection first
            try {
                DB::connection()->getPdo();
                $dbConnected = true;
            } catch (\Exception $e) {
                return "<h2>❌ Database Connection Failed</h2>
                        <p><strong>Error:</strong> " . $e->getMessage() . "</p>
                        <p><strong>Solution:</strong> Check your DATABASE_URL environment variable.</p>";
            }
            
            // Run migrations
            Artisan::call('migrate --force');
            $output = Artisan::output();
            
            return "<h2>✅ Migrations Run Successfully!</h2>
                    <pre>" . $output . "</pre>
                    <p><strong>Database:</strong> " . DB::connection()->getDatabaseName() . "</p>
                    <p><strong>Next Steps:</strong></p>
                    <ul>
                        <li><a href='/seed-database'>Seed Database</a></li>
                        <li><a href='/'>Go to Homepage</a></li>
                        <li><a href='/register'>Register a User</a></li>
                    </ul>";
                    
        } catch (\Exception $e) {
            return "<h2>❌ Error Running Migrations</h2>
                    <p><strong>Message:</strong> " . $e->getMessage() . "</p>
                    <p><strong>File:</strong> " . $e->getFile() . ":" . $e->getLine() . "</p>
                    <pre>" . $e->getTraceAsString() . "</pre>";
        }
    }
    
    public function seedDatabase()
    {
        try {
            Artisan::call('db:seed --force');
            $output = Artisan::output();
            
            return "<h2>✅ Database Seeded Successfully!</h2>
                    <pre>" . $output . "</pre>
                    <p><a href='/'>Go to Homepage</a></p>";
                    
        } catch (\Exception $e) {
            return "<h2>❌ Error Seeding Database</h2>
                    <p><strong>Message:</strong> " . $e->getMessage() . "</p>
                    <pre>" . $e->getTraceAsString() . "</pre>";
        }
    }
    
    public function fixPermissions()
    {
        try {
            // Try to fix storage permissions
            $commands = [
                'chmod -R 775 /var/www/html/storage',
                'chmod -R 775 /var/www/html/bootstrap/cache',
                'chown -R www-data:www-data /var/www/html/storage',
                'chown -R www-data:www-data /var/www/html/bootstrap/cache'
            ];
            
            $output = '';
            foreach ($commands as $cmd) {
                $output .= shell_exec($cmd . ' 2>&1') . "\n";
            }
            
            return "<h2>✅ Permissions Fixed!</h2>
                    <pre>" . $output . "</pre>
                    <p><a href='/run-migrations'>Run Migrations Now</a></p>";
                    
        } catch (\Exception $e) {
            return "<h2>❌ Error Fixing Permissions</h2>
                    <p><strong>Message:</strong> " . $e->getMessage() . "</p>";
        }
    }
    
    public function debug()
    {
        $info = [
            'app_env' => env('APP_ENV'),
            'app_debug' => env('APP_DEBUG'),
            'db_connection' => env('DB_CONNECTION'),
            'db_host' => env('DB_HOST'),
            'db_database' => env('DB_DATABASE'),
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        ];
        
        // Test database connection
        try {
            DB::connection()->getPdo();
            $info['db_status'] = 'Connected ✅';
            $info['db_name'] = DB::connection()->getDatabaseName();
        } catch (\Exception $e) {
            $info['db_status'] = 'Failed ❌';
            $info['db_error'] = $e->getMessage();
        }
        
        return response()->json($info);
    }
}