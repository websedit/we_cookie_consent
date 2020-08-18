<?php

namespace Websedit\WeCookieConsent\Hook;

/**
 * Prefill values after save
 */
class AfterSaveHook
{
    public function processDatamap_preProcessFieldArray(&$fieldArray, $table, $id, &$dataHandler)
    {
        if ($table === 'tx_wecookieconsent_domain_model_service' && $fieldArray['provider'] === 'google-tagmanager-service') {
            if ($fieldArray['title']) {
                $title = $fieldArray['title'];
            } else {
                $title = $id;
            }

            if ($fieldArray['gtm_tag_title'] === '') {
                $fieldArray['gtm_tag_title'] = $title . ' - Tag';
            }

            if ($fieldArray['gtm_trigger_title'] === '') {
                $fieldArray['gtm_trigger_title'] = $title . ' - Trigger';
            }
            if ($fieldArray['gtm_trigger_name'] === '') {
                $fieldArray['gtm_trigger_name'] = $fieldArray['provider'] . '-' . $id;
            }

            if ($fieldArray['gtm_variable_title'] === '') {
                $fieldArray['gtm_variable_title'] = $title . ' - Variable';
            }
            if ($fieldArray['gtm_variable_name'] === '') {
                $fieldArray['gtm_variable_name'] = $fieldArray['provider'] . '-' . $id;
            }
        }
    }
}