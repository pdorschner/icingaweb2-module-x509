<?php

namespace Icinga\Module\X509;

use Icinga\Web\Url;
use ipl\Html\Html;
use ipl\Translation\Translation;

/**
 * Table widget to display X.509 certificate usage
 */
class UsageTable extends Table
{
    use Translation;

    protected $defaultAttributes = ['class' => 'usage-table common-table table-row-selectable'];

    public function createColumns()
    {
        return [
            'valid' => [
                'attributes' => ['class' => 'icon-col'],
                'renderer' => function ($valid) {
                    $icon = $valid === 'yes' ? 'check' : 'block';

                    return Html::tag('i', ['class' => "icon icon-{$icon}"]);
                }
            ],

            'host' => [
                'column' => 'ip',
                'label' => $this->translate('Host'),
                'renderer' => function ($ip) {
                    return gethostbyaddr(inet_ntop($ip));
                }
            ],

            'ip' => [
                'label' => $this->translate('IP'),
                'renderer' => function ($ip) {
                    return inet_ntop($ip);
                }
            ],

            'port' => $this->translate('Port'),

            'sni_name' => $this->translate('SNI Name'),

            'subject' => $this->translate('Certificate'),

            'signature_algo' => [
                'label' => $this->translate('Signature Algorithm'),
                'renderer' => function ($algo, $data) {
                    return "$algo {$data['signature_hash_algo']}";
                }
            ],

            'valid_to' => [
                'attributes' => ['class' => 'expiration-col'],
                'label' => $this->translate('Expires'),
                'renderer' => function ($to, $data) {
                    return new ExpirationWidget($data['valid_from'], $to);
                }
            ]
        ];
    }

    protected function renderRow($row)
    {
        $tr = parent::renderRow($row);

        $tr->getAttributes()->add(['href' => Url::fromPath('x509/chain', ['cert' => $row['certificate_id'], 'target' => $row['target_id']])]);

        return $tr;
    }
}