<?php

namespace Database\Seeders;

use App\Models\Setting\Setting\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #region settings
        // seo
        Setting::create([
            'label' => 'Meta Title',
            'key' => 'meta_title',
            'value' => 'example',
            'description' => 'Change website meta title',
        ]);
        Setting::create([
            'label' => 'Meta description',
            'key' => 'meta_description',
            'value' => 'example',
            'value_type' => 'textarea',
            'description' => 'Change website meta description',
        ]);
        Setting::create([
            'label' => 'Meta keyword',
            'key' => 'meta_keyword',
            'value' => 'example',
            'description' => 'Change website meta keyword',
        ]);
        //
        Setting::create([
            'label' => 'name',
            'key' => 'name',
            'value' => 'example',
            'description' => 'Change website name',
        ]);
        Setting::create([
            'label' => 'slogan',
            'key' => 'slogan',
            'value' => 'example slogan',
            'description' => 'Change slogan',
        ]);

        Setting::create([
            'label' => 'Currency sign',
            'key' => 'currency_sign',
            'value' => '$',
            'description' => 'Change site currency sign',
        ]);
        Setting::create([
            'label' => 'Currency code',
            'key' => 'currency_code',
            'value' => 'usd',
            'description' => 'Change site currency code',
        ]);

        Setting::create([
            'label' => 'Address',
            'key' => 'address',
            'value' => 'Dhaka, Bangladesh',
            'description' => 'Change address',
        ]);
        Setting::create([
            'label' => 'Phone number',
            'key' => 'phone',
            'value' => 'phone',
            'description' => 'Change phone number',
        ]);
        Setting::create([
            'label' => 'Email',
            'key' => 'email',
            'value' => 'example@email.com',
            'description' => 'Change email',
        ]);
        Setting::create([
            'label' => 'Contact email',
            'key' => 'contact_email',
            'value' => 'example@email.com',
            'description' => 'Contact email used to get mail from user also show them.',
        ]);
        // with html editor       
        Setting::create([
            'label' => 'Copyright info',
            'key' => 'copyright',
            'value' => 'Copyright info',
            'value_type' => 'html',
            'description' => 'Change Copyright info',
        ]);
        // server
        Setting::create([
            'label' => 'Maintenance mode',
            'key' => 'maintenance_mode',
            'value' => 0,
            'value_type' => 'checkbox',
            'description' => 'Maintenance mode',
        ]);
        Setting::create([
            'label' => 'Maintenance mode message',
            'key' => 'maintenance_mode_message',
            'value' => 'Website is in maintenance',
            'value_type' => 'html',
            'description' => 'Maintenance mode message',
        ]);
        #endregion
        //
    }
}
