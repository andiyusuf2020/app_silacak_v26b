<!-- Contoh di view atau controller lain -->
<a href="<?= hash_url('profile/action', [
                'user_id' => 123,
                'action' => 'edit'
            ]) ?>">Edit Profile</a>