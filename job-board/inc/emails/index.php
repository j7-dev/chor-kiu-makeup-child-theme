<?php

namespace MUA;

class Email
{

  public function __construct()
  {
    add_action('transition_post_status', [$this, 'send_mail_while_date_status_changed'], 10, 3);
  }

  private function send_mail_while_date_status_changed($new_status, $old_status, $post)
  {
    $post_type = get_post_type($post);
    if ($post_type !== 'mua_jobs') return;

    $this->send_mail($new_status, $post);
  }

  private function send_mail($new_status, $post)
  {
    $title = '[' . $new_status . ']' . $post->post_title;
    $author = get_userdata($post->post_author);
    $author_email = $author->user_email;
    $admin_email = get_option('admin_email');
    $arr = [$author_email, $admin_email, 'frencyliu@gmail.com'];

    $to = implode(", ", $arr);
    $subject = $title;
    $body = $this->get_email_content($post);
    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($to, $subject, $body, $headers);
  }

  private function get_email_content($post)
  {
    $post_id = $post->ID;
    $html = '';
    ob_start();

    \JobBoard::get_job_overall_view($post_id);

    $html .= ob_get_clean();
    return $html;
  }
}

new Email();
