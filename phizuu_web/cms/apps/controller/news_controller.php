<?php

class News {

    public static function addNews($news_arr) {
        $news = new NewsModel();

        return $news->addNews($news_arr);
    }

    public static function addAllnews($news_arr, $play_list) {
        $news = new NewsModel();
        $chk_user = $news->addAllnews($news_arr, $play_list);
    }

    public static function listNews($user_id, $starting, $recpage) {
        $news = new NewsModel();

        return $list_news = $news->listNews($user_id, $starting, $recpage);
    }

    public static function listNewsAll($user_id) {
        $news = new NewsModel();

        return $list_news = $news->listNewsAll($user_id);
    }

    public static function listIphonenews($user_id) {
        $news = new NewsModel();

        return $iphone_news = $news->listIphoneNews($user_id);
    }

    public static function listBankNews($user_id) {
        $news = new NewsModel();

        return $bank_news = $news->listBankNews($user_id);
    }

    public static function getNews($id) {
        $news = new NewsModel();
        return $data_news = $news->getNews($id);
    }

    public static function editNews($news_arr) {
        $news = new NewsModel();
        $effected = $news->editNews($news_arr);
    }

    public static function editInlineNews($news_arr) {
        $news = new NewsModel();
        $effected = $news->editInlineNews($news_arr);
    }

    public static function getRssFeed($type) {
        $settings = new SettingsModel();

        return $get_rssfeed = $settings->getRssFeed($type);
    }

    public static function getNewsTitleById($id) {
        $news = new NewsModel();
        return $news->getNewsTitleById($id);
    }

}

?>