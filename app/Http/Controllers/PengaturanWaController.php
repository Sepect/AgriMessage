<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FonnteGatewayService;
use App\Models\WaSetting;

class PengaturanWaController extends Controller
{
    public function index(FonnteGatewayService $fonnteService)
    {
        $deviceStatus = $fonnteService->getDeviceStatus();

        if (isset($deviceStatus['device_status']) && $deviceStatus['device_status'] === 'disconnect') {
            $qrData = $fonnteService->getQrCode();
            if (isset($qrData['url'])) {
                $deviceStatus['qr'] = $qrData['url'];
            } elseif (isset($qrData['reason'])) {
                $deviceStatus['qr_error'] = $qrData['reason'] === 'rate limit'
                    ? 'Terlalu sering memuat QR. Harap tunggu 1-2 menit sebelum merefresh halaman.'
                    : $qrData['reason'];
            }
        }

        $autoReplyEnabled = WaSetting::where('key', 'auto_reply_enabled')->value('value') ?? '0';
        $autoReplyMessage = WaSetting::where('key', 'auto_reply_message')->value('value') ?? 'Halo! Terima kasih telah menghubungi SAPA MASPUL. Saat ini kami sedang di luar jam operasional. Kami akan membalas pesan Anda pada jam kerja (Senin-Jumat, 08:00 - 16:00 WIB).';

        return view('pengaturan-wa.index', compact('deviceStatus', 'autoReplyEnabled', 'autoReplyMessage'));
    }

    public function disconnect(FonnteGatewayService $fonnteService)
    {
        $response = $fonnteService->disconnectDevice();

        if ($response && isset($response['status']) && $response['status']) {
            return redirect()->route('pengaturan-wa.index')->with('success', 'Perangkat berhasil diputuskan.');
        }

        return redirect()->route('pengaturan-wa.index')->with('error', 'Gagal memutuskan perangkat Fonnte.');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'auto_reply_enabled' => 'nullable|boolean',
            'auto_reply_message' => 'required|string',
            'fonnte_token' => 'nullable|string',
        ]);

        WaSetting::updateOrCreate(
            ['key' => 'auto_reply_enabled'],
            ['value' => $request->has('auto_reply_enabled') ? '1' : '0']
        );

        WaSetting::updateOrCreate(
            ['key' => 'auto_reply_message'],
            ['value' => $request->auto_reply_message]
        );

        if ($request->has('fonnte_token') && !empty($request->fonnte_token)) {
            WaSetting::updateOrCreate(
                ['key' => 'fonnte_token'],
                ['value' => $request->fonnte_token]
            );
        }

        return redirect()->route('pengaturan-wa.index')->with('success', 'Pengaturan auto-reply berhasil disimpan.');
    }
}
