<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Profile Management
            'edit_profile',
            'view_profile',

            // Event Management
            'create_events',
            'edit_events',
            'delete_events',
            'view_events',
            'publish_events',
            'manage_event_tickets',
            'view_event_analytics',

            // Venue Management
            'create_venues',
            'edit_venues',
            'delete_venues',
            'view_venues',
            'manage_venue_calendar',
            'view_venue_analytics',

            // Artist Management
            'create_artist_profile',
            'edit_artist_profile',
            'delete_artist_profile',
            'view_artist_profile',
            'manage_artist_calendar',
            'view_artist_analytics',

            // Ticket Management
            'create_tickets',
            'edit_tickets',
            'delete_tickets',
            'view_tickets',
            'manage_ticket_pricing',
            'process_refunds',

            // Review & Rating
            'create_reviews',
            'edit_reviews',
            'delete_reviews',
            'moderate_reviews',

            // Content Management
            'manage_content',
            'manage_announcements',
            'manage_promotions',

            // System Management
            'access_admin_panel',
            'manage_roles',
            'manage_permissions',
            'view_system_logs',
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and Assign Permissions

        // 1. User Role (Basic authenticated user)
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions([
            'edit_profile',
            'view_profile',
            'view_events',
            'view_venues',
            'view_artist_profile',
            'create_reviews',
            'edit_reviews',
            'view_tickets',
        ]);

        // 2. Artist Role
        $artistRole = Role::firstOrCreate(['name' => 'artist']);
        $artistRole->syncPermissions([
            'edit_profile',
            'view_profile',
            'create_artist_profile',
            'edit_artist_profile',
            'view_artist_profile',
            'manage_artist_calendar',
            'view_artist_analytics',
            'view_events',
            'create_events',
            'edit_events',
            'view_tickets',
            'manage_content',
        ]);

        // 3. Event Organizer Role
        $eventOrganizerRole = Role::firstOrCreate(['name' => 'event_organizer']);
        $eventOrganizerRole->syncPermissions([
            'edit_profile',
            'view_profile',
            'create_events',
            'edit_events',
            'delete_events',
            'view_events',
            'publish_events',
            'manage_event_tickets',
            'view_event_analytics',
            'create_tickets',
            'edit_tickets',
            'delete_tickets',
            'view_tickets',
            'manage_ticket_pricing',
            'manage_content',
            'manage_promotions',
        ]);

        // 4. Venue Manager Role
        $venueManagerRole = Role::firstOrCreate(['name' => 'venue_manager']);
        $venueManagerRole->syncPermissions([
            'edit_profile',
            'view_profile',
            'create_venues',
            'edit_venues',
            'view_venues',
            'manage_venue_calendar',
            'view_venue_analytics',
            'view_events',
            'create_events',
            'edit_events',
            'manage_content',
            'manage_promotions',
        ]);

        // 5. Admin Role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions([
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'access_admin_panel',
            'manage_roles',
            'manage_content',
            'manage_announcements',
            'manage_promotions',
            'moderate_reviews',
            'process_refunds',
            'view_system_logs',
            'manage_settings',
            // Include all event, venue, and artist management permissions
            'create_events',
            'edit_events',
            'delete_events',
            'view_events',
            'publish_events',
            'create_venues',
            'edit_venues',
            'delete_venues',
            'view_venues',
            'create_artist_profile',
            'edit_artist_profile',
            'delete_artist_profile',
            'view_artist_profile',
        ]);

        // 6. Super Admin Role
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        // Super Admin gets all permissions via Gate::before rule
        $superAdminRole->syncPermissions(Permission::all());
    }
}
