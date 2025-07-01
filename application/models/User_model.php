<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $table = 'users';

    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id_user) {
        return $this->db->get_where($this->table, ['id_user' => $id_user])->row();
    }

    public function get_by_username($username) {
        return $this->db->get_where($this->table, ['username' => $username])->row();
    }

    public function insert($data) {
        // Hash password sebelum disimpan
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        return $this->db->insert($this->table, $data);
    }

    public function update($id_user, $data) {
        // Hash password jika diubah
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } elseif (isset($data['password']) && empty($data['password'])) {
            unset($data['password']); // Jangan update password jika field kosong
        }
        $this->db->where('id_user', $id_user);
        return $this->db->update($this->table, $data);
    }

    public function delete($id_user) {
        // Periksa apakah user terkait dengan transaksi sebelum menghapus
        // Ini penting untuk integritas data, user yang sudah punya transaksi sebaiknya tidak dihapus
        // Atau bisa juga di-nonaktifkan saja statusnya, bukan dihapus permanen
        $this->db->where('user_id', $id_user);
        $count = $this->db->count_all_results('transaksi');
        if ($count > 0) {
            // Sebaiknya return false atau throw exception, atau implementasikan soft delete/deactivate
            // Untuk saat ini kita return false
            return false;
        }

        $this->db->where('id_user', $id_user);
        return $this->db->delete($this->table);
    }

    public function count_all() {
        return $this->db->count_all($this->table);
    }

    public function login($username, $password) {
        $user = $this->get_by_username($username);
        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

    public function is_username_exists($username, $id_user = null) {
        $this->db->where('username', $username);
        if ($id_user !== null) {
            $this->db->where('id_user !=', $id_user);
        }
        return $this->db->count_all_results($this->table) > 0;
    }
}
?>
