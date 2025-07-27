<?php
require('routeros_api.class.php');

class MikrotikCRUD {
    private $API;
    private $ip;
    private $user;
    private $pass;

    public function __construct($ip, $user, $pass) {
        $this->API = new RouterosAPI();
        $this->ip = $ip;
        $this->user = $user;
        $this->pass = $pass;
    }

    private function connect() {
        return $this->API->connect($this->ip, $this->user, $this->pass);
    }

    private function disconnect() {
        $this->API->disconnect();
    }

    /** ✅ CREATE: Tambah user PPPoE */
    public function createUser($username, $password, $paket) {
        if ($this->connect()) {
            $this->API->comm("/ppp/secret/add", [
                "name"     => $username,
                "password" => $password,
                "service"  => "pppoe",
                "profile"  => $paket
            ]);
            echo "✅ User '$username' berhasil ditambahkan dengan paket '$paket'.";
            $this->disconnect();
        } else {
            echo "❌ Gagal konek ke MikroTik.";
        }
    }

    /** ✅ READ: Lihat semua user PPPoE */
    public function readUsers() {
        if ($this->connect()) {
            $ARRAY = $this->API->comm("/ppp/secret/print");
            if (!empty($ARRAY)) {
                echo "✅ Daftar User PPPoE:\n";
                foreach ($ARRAY as $user) {
                    echo "-----------------------------\n";
                    echo "User:      " . $user['name'] . "\n";
                    echo "Service:   " . $user['service'] . "\n";
                    echo "Profile:   " . $user['profile'] . "\n";
                    echo "Disabled:  " . ($user['disabled'] === "true" ? "Ya" : "Tidak") . "\n";
                }
            } else {
                echo "❌ Tidak ada user PPPoE yang terdaftar.";
            }
            $this->disconnect();
        } else {
            echo "❌ Gagal konek ke MikroTik.";
        }
    }

    /** ✅ UPDATE: Ubah profile atau password user PPPoE */
    public function updateUser($username, $newProfile = null, $newPassword = null) {
        if ($this->connect()) {
            $users = $this->API->comm("/ppp/secret/print", ["?name" => $username]);
            if (!empty($users)) {
                $updateData = [".id" => $users[0][".id"]];
                if ($newProfile) $updateData["profile"] = $newProfile;
                if ($newPassword) $updateData["password"] = $newPassword;

                $this->API->comm("/ppp/secret/set", $updateData);
                echo "✅ User '$username' berhasil diupdate.";
            } else {
                echo "❌ User '$username' tidak ditemukan.";
            }
            $this->disconnect();
        } else {
            echo "❌ Gagal konek ke MikroTik.";
        }
    }

    /** ✅ DELETE: Hapus user PPPoE */
    public function deleteUser($username) {
        if ($this->connect()) {
            $users = $this->API->comm("/ppp/secret/print", ["?name" => $username]);
            if (!empty($users)) {
                $this->API->comm("/ppp/secret/remove", [".id" => $users[0][".id"]]);
                echo "✅ User '$username' berhasil dihapus.";
            } else {
                echo "❌ User '$username' tidak ditemukan.";
            }
            $this->disconnect();
        } else {
            echo "❌ Gagal konek ke MikroTik.";
        }
    }
    public function updatePaket($username, $paket) {
        if ($this->connect()) {
            $users = $this->API->comm("/ppp/secret/print", ["?name" => $username]);
            if (!empty($users)) {
                $this->API->comm("/ppp/secret/set", [
                    ".id" => $users[0][".id"],
                    "profile" => $paket
                ]);
                // echo "✅ User '$username' berhasil diubah ke paket '$paket'.";
            } else {
                // echo "❌ User '$username' tidak ditemukan.";
            }
            $this->disconnect();
        } else {
            echo "❌ Gagal konek ke MikroTik.";
        }
    }
}

$mt = new MikrotikCRUD('192.168.43.112', 'api-user', 'api123');
