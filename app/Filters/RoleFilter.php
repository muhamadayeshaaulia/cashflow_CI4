<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah user SUDAH LOGIN?
        if (!session()->get('isLoggedIn')) {
            // Jika belum, tendang kembali ke halaman login dengan pesan error
            session()->setFlashdata('error', 'Akses ditolak! Silakan login terlebih dahulu.');
            return redirect()->to('/login');
        }

        // Cek apakah ROLE (Hak Akses) user sesuai dengan yang diizinkan rute?
        if ($arguments) {
            $role_user = session()->get('role');
            
            // Jika role user saat ini tidak ada di dalam daftar argumen rute yang diizinkan
            if (!in_array($role_user, $arguments)) {
                
                // Tendang kembali ke dashboard masing-masing sesuai role-nya
                $url_dashboard = ($role_user == 'admin_keuangan') ? '/admin' : '/' . $role_user;
                return redirect()->to($url_dashboard);
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
