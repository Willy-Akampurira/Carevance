<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Helper: get effective setting value.
     * Always queries DB first, then falls back to config.
     */
    protected function getSetting(string $key, $default = null)
    {
        return Setting::where('setting_key', $key)->value('value')
            ?? config("settings.$key", $default);
    }

    /**
     * Show Clinic Information settings form.
     */
    public function clinic()
    {
        return view('settings.clinic', [
            'name'       => $this->getSetting('clinic_name', 'Supreme-Clinic'),
            'tagline'    => $this->getSetting('clinic_tagline', ''),
            'address'    => $this->getSetting('clinic_address', ''),
            'phone'      => $this->getSetting('clinic_phone', ''),
            'email'      => $this->getSetting('clinic_email', ''),
            'hours'      => $this->getSetting('clinic_hours', ''),
            'logo'       => $this->getSetting('clinic_logo', 'logo.png'),
            'welcome_bg' => $this->getSetting('welcome_bg', 'pharmacare.jpeg'),
            'guest_bg'   => $this->getSetting('guest_bg', 'pharmacare.jpeg'),
        ]);
    }

    /**
     * Update Clinic Information settings.
     */
    public function updateClinic(Request $request)
    {
        $validated = $request->validate([
            'logo'        => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'welcome_bg'  => 'nullable|image|mimes:png,jpg,jpeg|max:4096',
            'guest_bg'    => 'nullable|image|mimes:png,jpg,jpeg|max:4096',
            'name'        => 'required|string|max:255',
            'tagline'     => 'nullable|string|max:255',
            'address'     => 'nullable|string|max:500',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255',
            'hours'       => 'nullable|string|max:255',
        ]);

        Setting::setValue('clinic_name', $validated['name']);
        Setting::setValue('clinic_tagline', $validated['tagline'] ?? '');
        Setting::setValue('clinic_address', $validated['address'] ?? '');
        Setting::setValue('clinic_phone', $validated['phone'] ?? '');
        Setting::setValue('clinic_email', $validated['email'] ?? '');
        Setting::setValue('clinic_hours', $validated['hours'] ?? '');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            Setting::setValue('clinic_logo', $path);
        }
        if ($request->hasFile('welcome_bg')) {
            $path = $request->file('welcome_bg')->store('backgrounds', 'public');
            Setting::setValue('welcome_bg', $path);
        }
        if ($request->hasFile('guest_bg')) {
            $path = $request->file('guest_bg')->store('backgrounds', 'public');
            Setting::setValue('guest_bg', $path);
        }

        return redirect()->route('settings.clinic')->with('success', 'Clinic information updated successfully.');
    }

    /**
     * Show Invoice Settings form.
     */
    public function invoice()
    {
        return view('settings.invoice', [
            'prefix'      => $this->getSetting('invoice_prefix', ''),
            'tax'         => $this->getSetting('invoice_tax', 0),
            'currency'    => $this->getSetting('invoice_currency', 'UGX'),
            'footer_note' => $this->getSetting('invoice_footer_note', ''),
            'discount'    => $this->getSetting('invoice_discount', 0),
        ]);
    }

    /**
     * Update Invoice Settings.
     */
    public function updateInvoice(Request $request)
    {
        $validated = $request->validate([
            'prefix'      => 'nullable|string|max:20',
            'tax'         => 'nullable|numeric|min:0',
            'currency'    => 'nullable|string|max:10',
            'footer_note' => 'nullable|string|max:500',
            'discount'    => 'nullable|boolean',
        ]);

        Setting::setValue('invoice_prefix', $validated['prefix'] ?? '');
        Setting::setValue('invoice_tax', $validated['tax'] ?? 0);
        Setting::setValue('invoice_currency', $validated['currency'] ?? 'UGX');
        Setting::setValue('invoice_footer_note', $validated['footer_note'] ?? '');
        Setting::setValue('invoice_discount', $validated['discount'] ?? 0);

        return redirect()->route('settings.invoice')->with('success', 'Invoice settings updated successfully.');
    }

    /**
     * Show Theme Settings form.
     */
    public function theme()
    {
        return view('settings.theme', [
            'primary_color'   => $this->getSetting('theme_primary_color', '#0f766e'),
            'secondary_color' => $this->getSetting('theme_secondary_color', '#facc15'),
            'font'            => $this->getSetting('theme_font', 'Figtree'),
            'logo_position'   => $this->getSetting('theme_logo_position', 'left'),
            'custom_css'      => $this->getSetting('theme_custom_css', ''),
        ]);
    }

    /**
     * Update Theme Settings.
     */
    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'primary_color'   => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'font'            => 'nullable|string|max:50',
            'logo_position'   => 'nullable|in:left,center,right',
            'custom_css'      => 'nullable|string',
        ]);

        Setting::setValue('theme_primary_color', $validated['primary_color'] ?? '#0f766e');
        Setting::setValue('theme_secondary_color', $validated['secondary_color'] ?? '#facc15');
        Setting::setValue('theme_font', $validated['font'] ?? 'Figtree');
        Setting::setValue('theme_logo_position', $validated['logo_position'] ?? 'left');
        Setting::setValue('theme_custom_css', $validated['custom_css'] ?? '');

        return redirect()->route('settings.theme')->with('success', 'Theme settings updated successfully.');
    }

    /**
     * Show Footer Settings form.
     */
    public function footer()
    {
        return view('settings.footer', [
            'footer_text'   => $this->getSetting('footer_text', 'Designed & Developed by Supreme-Clinic Team'),
            'facebook'      => $this->getSetting('footer_facebook', ''),
            'twitter'       => $this->getSetting('footer_twitter', ''),
            'whatsapp'      => $this->getSetting('footer_whatsapp', ''),
            'contact_info'  => $this->getSetting('footer_contact_info', ''),
        ]);
    }

    /**
     * Update Footer Settings.
     */
    public function updateFooter(Request $request)
    {
        $validated = $request->validate([
            'footer_text' => 'nullable|string|max:500',
            'facebook'    => 'nullable|url',
            'twitter'     => 'nullable|url',
            'whatsapp'    => 'nullable|url',
            'contact_info'=> 'nullable|string|max:500',
        ]);

        Setting::setValue('footer_text', $validated['footer_text'] ?? 'Designed & Developed by Supreme-Clinic Team');
        Setting::setValue('footer_facebook', $validated['facebook'] ?? '');
        Setting::setValue('footer_twitter', $validated['twitter'] ?? '');
        Setting::setValue('footer_whatsapp', $validated['whatsapp'] ?? '');
        Setting::setValue('footer_contact_info', $validated['contact_info'] ?? '');

        return redirect()->route('settings.footer')->with('success', 'Footer settings updated successfully.');
    }
}
