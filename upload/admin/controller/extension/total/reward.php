<?php
class ControllerExtensionTotalReward extends Controller {
    private array $error = [];

    public function index(): void {
        $this->load->language('extension/total/reward');

        $this->document->setTitle($this->language->get('heading_title'));

        // Settings
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('total_reward', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/total/reward', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['action'] = $this->url->link('extension/total/reward', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', true);

        if (isset($this->request->post['total_reward_status'])) {
            $data['total_reward_status'] = $this->request->post['total_reward_status'];
        } else {
            $data['total_reward_status'] = $this->config->get('total_reward_status');
        }

        if (isset($this->request->post['total_reward_sort_order'])) {
            $data['total_reward_sort_order'] = $this->request->post['total_reward_sort_order'];
        } else {
            $data['total_reward_sort_order'] = $this->config->get('total_reward_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/total/reward', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/total/reward')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}