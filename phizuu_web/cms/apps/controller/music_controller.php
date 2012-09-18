<?php

class Music {
    public static function addMusic($music_arr) {
        $music = new MusicModel();

        $chk_user=$music -> addMusic($music_arr);

    }

    public static function addAllMusic($music_arr) {
        $music = new MusicModel();

        $chk_user=$music -> addAllMusic($music_arr);

    }

    public static function uploadMusic($music_arr) {
        $music = new MusicModel();

        return $music -> uploadMusic($music_arr);

    }

    public static function listBankMusic($user_id) {
        $music = new MusicModel();

        return $bank_musics=$music -> listBankMusic($user_id);

    }

    public static function listIphoneMusic($user_id) {
        $music = new MusicModel();

        return $all_music=$music -> listIphoneMusic($user_id);

    }

    public static function listAllMusic($user_id) {
        $music = new MusicModel();

        return $iphone_musics=$music -> listAllMusic($music_arr);

    }

    public static function getMusicStorage($user_id) {
        $music = new MusicModel();

        return $music_storage=$music -> getMusicStorage($user_id);

    }

    public static function getMusic($id) {
        $music = new MusicModel();
        return $data_music=$music -> getMusic($id);

    }

    public static function getMusicByUri($uri) {
        $music = new MusicModel();
        return $data_music=$music -> getMusicByUri($uri);

    }

    public static function getBoxAccount() {
        $music = new MusicModel();
        return $data_music_user=$music -> getBoxAccount();

    }

    public static function getCoverImage($user_id) {
        $music = new MusicModel();

        return $music -> getCoverImage($user_id);

    }
    
    public static function getMusicName($id){
        $music = new MusicModel();
        return $music->getMusicName($id);
    }
}
?>