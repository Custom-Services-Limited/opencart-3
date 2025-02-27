<?php
class ControllerExtensionModulePayPalSmartButton extends Controller {
    private array $error = [];

    public function index(): void {
        $this->load->language('extension/module/paypal_smart_button');

        // Settings
        $this->load->model('setting/setting');

        // PayPal Smart Button
        $this->load->model('extension/module/paypal_smart_button');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_paypal_smart_button', $this->request->post);

            $this->session->data['success'] = $this->language->get('success_save');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
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
            'text' => $this->language->get('text_extensions'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/paypal_smart_button', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['action'] = $this->url->link('extension/module/paypal_smart_button', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        if (isset($this->request->post['module_paypal_smart_button_status'])) {
            $data['status'] = $this->request->post['module_paypal_smart_button_status'];
        } else {
            $data['status'] = $this->config->get('module_paypal_smart_button_status');
        }

        // Setting
        $_config = new \Config();
        $_config->load('paypal_smart_button');

        $data['setting'] = $_config->get('paypal_smart_button_setting');

        if (isset($this->request->post['module_paypal_smart_button_setting'])) {
            $data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->request->post['module_paypal_smart_button_setting']);
        } else {
            $data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('module_paypal_smart_button_setting'));
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/paypal_smart_button', $data));
    }

    public function install(): void {
        // Settings
        $this->load->model('setting/setting');

        // PayPal Smart Button
        $this->load->model('extension/module/paypal_smart_button');

        $this->model_extension_module_paypal_smart_button->install();

        $setting['module_paypal_smart_button_status'] = 0;

        $this->model_setting_setting->editSetting('module_paypal_smart_button', $setting);
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/paypal_smart_button')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}