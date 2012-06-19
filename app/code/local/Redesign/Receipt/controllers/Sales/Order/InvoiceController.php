<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Adminhtml sales order edit controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
require_once('Mage/Adminhtml/controllers/Sales/Order/InvoiceController.php');
require_once(Mage::getBaseDir() . '/lib/dompdf/dompdf_config.inc.php');


include_once(Mage::getBaseDir() . '/lib/dompdf/include/abstract_renderer.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/attribute_translator.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/frame.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/frame_tree.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/frame_reflower.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/frame_decorator.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/text_frame_decorator.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/block_frame_decorator.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/inline_frame_decorator.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/block_frame_reflower.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/positioner.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/null_positioner.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/block_positioner.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/inline_positioner.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/table_cell_positioner.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/table_frame_reflower.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/table_cell_frame_reflower.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/table_row_frame_reflower.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/text_frame_reflower.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/inline_frame_reflower.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/table_row_frame_decorator.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/table_cell_frame_decorator.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/page_frame_reflower.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/cellmap.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/table_frame_decorator.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/frame_factory.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/page_frame_decorator.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/block_frame_decorator.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/block_renderer.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/image_cache.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/text_renderer.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/table_cell_renderer.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/inline_renderer.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/renderer.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/canvas.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/cpdf_adapter.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/canvas_factory.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/font_metrics.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/style.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/stylesheet.cls.php');
include_once(Mage::getBaseDir() . '/lib/dompdf/include/dompdf.cls.php');

class Redesign_Receipt_Sales_Order_InvoiceController extends Mage_Adminhtml_Sales_Order_InvoiceController {

    public function printAction() {
        $invoice_id = $this->getRequest()->getParam('invoice_id');
        $invoice_obj = Mage::getModel('sales/order_invoice')->load($invoice_id);
        $customer = Mage::getModel('customer/customer')->load($invoice_obj->getOrder()->getCustomerId());
        $customer_address = Mage::getModel('customer/address')->load($customer->getDefaultBilling());

        $invoice = '
        <div style="color:#330066">
        <table width="600">
            <tr>
                <td colspan="2" style="padding-left:245px">Seria ' . Mage::getStoreConfig('receipt/invoicesettings/seria') . ' Nr. ' . $invoice_obj->getIncrementId() . '</td>
            </tr>
            <tr>
            <td width="350">
                Furnizor: ' . Mage::getStoreConfig('receipt/invoicesettings/nume_firma') . ' <br>
                Nr. reg. com: ' . Mage::getStoreConfig('receipt/invoicesettings/registru_comert') . ' <br>
                C.I.F: ' . Mage::getStoreConfig('receipt/invoicesettings/cif') . ' <br>
                Capital social: ' . Mage::getStoreConfig('receipt/invoicesettings/capital_social') . ' RON <br>
                Sediul: ' . Mage::getStoreConfig('receipt/invoicesettings/sediu') . ' <br>
                Cod IBAN: ' . Mage::getStoreConfig('receipt/invoicesettings/cod_iban') . ' <br>
                Banca: ' . Mage::getStoreConfig('receipt/invoicesettings/banca') . ' <br>
                Web: www.redesignmob.ro <br>
            </td>
            <td>
                Cumparator: ' . $customer->getFirstname() . ' ' . $customer->getLastname() . ' <br>
                Nr. reg. com: <br>
                C.I.F:  <br>
                Sediul: ' . $customer_address->getStreet(1) . ', ' . $customer_address->getCity() . ', ' . $customer_address->getRegion() . ' <br>
                Cod IBAN: <br>
                Banca: <br>
            </td>
            </tr>
          </table>
          <div style="width:600px;text-align:center">
                <span style="font-size:12px;font-weight:bold">FACTURA FISCALA</span>
          </div>
        <div style="margin:10px 0 0 210px;padding:5px;border:1px solid;width:170px">
            Nr. facturii: ' . $invoice_obj->getIncrementId() . '<br>
            Data (ziua, luna, anul): ' . date('d.m.Y') . '<br>
            Nr. aviz insotire a marfii:<br>
        </div>
        <table cellspacing="0" style="margin-top:20px;border-collapse: collapse;padding:0;text-align:center;vertical-align:middle;font-size:10px" width="600" border="1">
            <tr>
                <td width="30" height="50">Nr.<br> crt.</td>
                <td width="185">Denumirea produselor<br>sau a serviciilor</td>
                <td width="40">U.M.</td>
                <td width="80">Cantitatea</td>
                <td width="80">Pretul unitar<br>(fara T.V.A)<br>-lei-</td>
                <td width="80">Valoarea<br>-lei-</td>
                <td width="80">Valoarea<br>T.V.A<br>-lei-</td>
            </tr>
            <tr>
                <td height="20">0</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5 (3x4)</td>
                <td>6</td>
            </tr>
            ';
        $total_without_tax = 0;
        $total_tax = 0;
        foreach ($invoice_obj->getOrder()->getAllItems() as $index => $item) {
            $total_without_tax += $item->getRowTotal();
            $total_tax += $item->getTaxAmount();
            $height = $index == (count($invoice_obj->getOrder()->getAllItems()) - 1) ? 328 - (count($invoice_obj->getOrder()->getAllItems()) - 1) * 20 : 20;
            $invoice .=
                    '<tr style="border-top:bottom">
                    <td style="border-top: 0px; vertical-align:top" height="' . $height . '">' . ($index + 1) . '.</td>
                    <td style="border-top: 0px; vertical-align:top" height="20">' . $item->getName() . '</td>
                    <td style="border-top: 0px; vertical-align:top" height="20">buc.</td>
                    <td style="border-top: 0px; vertical-align:top" height="20">' . intval($item->getQtyInvoiced()) . '</td>
                    <td style="border-top: 0px; vertical-align:top" height="20">' . number_format($item->getPrice(), 2, ',', '.') . '</td>
                    <td style="border-top: 0px; vertical-align:top" height="20">' . number_format($item->getRowTotal(), 2, ',', '.') . '</td>
                    <td style="border-top: 0px; vertical-align:top" height="20">' . number_format($item->getTaxAmount(), 2, ',', '.') . '</td>
                </tr>';
        }

        $invoice .= '</table>
        <table cellspacing="0" width="600" border="1" style="border-collapse: collapse;padding:0;">
            <tr>
                <td width="100" height="100" style="font-size:10px;">&nbsp;Semnatura si<br>&nbsp;stampila<br>&nbsp;furnizorului</td>
                <td width="241" style="font-size:10px;">
                    &nbsp;Date privind expeditia<br>
                    &nbsp;Numele delegatului ...........................................................<br>
                    &nbsp;...........................................................................................<br>
                    &nbsp;B.I./C.I. seria ...... nr. .......... eliberat(a) ............................<br>
                    &nbsp;Mijloc de transport .......................... nr ............................<br>
                    &nbsp;Expedierea s-a facut in prezenta noastra la data de .........<br>
                    &nbsp;Semnaturile .......................................................................<br>
                </td>
                <td width="82" style="padding:0">
                    <table height="200" width="100%" style="border-collapse: collapse;padding:0;" cellpadding="0" cellspacing="0" border="1">
                        <tr>
                            <td height="50" style="font-size:10px;">&nbsp;Total din care<br>&nbsp;accize:</td>
                        </tr>
                        <tr>
                            <td height="50" style="font-size:10px;">&nbsp;Semnatura<br>&nbsp;de primire</td>
                        </tr>
                    </table>
                </td>
                <td style="padding:0" valign="top" width="165">
                    <table height="190" width="100%" style="border-collapse: collapse;padding:0;" cellpadding="0" cellspacing="0" border="1">
                        <tr>
                            <td height="50" style="padding:0">
                                <table height="48" width="100%" style="border-collapse: collapse;padding:0;" cellpadding="0" cellspacing="0" border="1">
                                    <tr>
                                        <td style="vertical-align:middle" align="center" width="50%" height="24">' . number_format($total_without_tax, 2, ',', '.') . '</td>
                                        <td style="vertical-align:middle" align="center" height="24">' . number_format($total_tax, 2, ',', '.') . '</td>
                                    </tr>
                                    <tr>
                                        <td height="24"></td>
                                        <td height="24"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td height="50" style="font-size:10px;">&nbsp;Total de plata <span style="font-size:14px;">' . number_format(($total_without_tax + $total_tax), 2, ',', '.') . '</span><br>&nbsp;(col.5+col.6)</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </div>
        ';

        if ($this->getRequest()->getParam('receipt')) {
//            $invoice .= '<div><table style="margin-top: 20px;border-collapse: collapse;padding:0;" cellpadding="0" cellspacing="0" border="1">
//                            <tr>
//                                <td>Chitanta nr. ' . Mage::getStoreConfig('receipt/invoicesettings/nr_receipt') . '</td>
//                             </tr>
//                           </table>
//                          </div>  
//                        ';
            $decimal_part = number_format((($total_without_tax + $total_tax) - floor(($total_without_tax + $total_tax))), 2);
            $parts = explode('.', $decimal_part);
            $decimal_part = $parts[1];
            $integer_part = intval($total_without_tax + $total_tax);
            $invoice .= '<div style="padding:5px;margin-top:10px;border:1px solid #330066;color:#330066;width:400px">
                <table width="100%">
                    <tr>
                        <td colspan="2" style="padding-left:245px">Seria ' . Mage::getStoreConfig('receipt/invoicesettings/seria') . ' Nr. ' . Mage::getStoreConfig('receipt/invoicesettings/nr_receipt') . '</td>
                    </tr>
                    <tr>
                    <td width="250">
                        Furnizor: ' . Mage::getStoreConfig('receipt/invoicesettings/nume_firma') . ' <br>
                        Nr. reg. com: ' . Mage::getStoreConfig('receipt/invoicesettings/registru_comert') . ' <br>
                        C.I.F: ' . Mage::getStoreConfig('receipt/invoicesettings/cif') . ' <br>
                        Capital social: ' . Mage::getStoreConfig('receipt/invoicesettings/capital_social') . ' RON <br>
                        Sediul: ' . Mage::getStoreConfig('receipt/invoicesettings/sediu') . ' <br>
                        Cod IBAN: ' . Mage::getStoreConfig('receipt/invoicesettings/cod_iban') . ' <br>
                        Banca: ' . Mage::getStoreConfig('receipt/invoicesettings/banca') . ' <br>
                        Web: www.redesignmob.ro <br>
                    </td>
                    <td>
                        <div style="margin-left:10px;width:100px;text-align:center">
                            <span style="font-size:18px;font-weight:bold">CHITANTA</span>
                        </div>
                        <div style="margin:10px 0 0 10px;padding:5px;border:1px solid;width:100px">
                            Nr. : ' . Mage::getStoreConfig('receipt/invoicesettings/nr_receipt') . '<br>
                            Data : ' . date('d.m.Y') . '
                        </div>
                    </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div style="padding:5px;margin-top:5px;width:100%;height:110px;border:1px solid #330066">
                                Am primit de la: ' . $customer->getFirstname() . ' ' . $customer->getLastname() . ' <br>
                                Nr. ord. reg. com: ....... - ...... C.I.F. ............ - ..............................................`<br>
                                Suma de: ' . number_format(($total_without_tax + $total_tax), 2, ',', '.') . ' adica ' . $this->getWords($integer_part, ' ') . ' lei ' . ($decimal_part > 0 ? 'si ' . $this->getWords(intval($decimal_part), ' ') . ' bani' : '') . ' <br>
                                Reprezentand: Contravaloare factura fiscala nr. ' . $invoice_obj->getIncrementId() . ' / ' . date('d.m.Y') . ' <br><br>
                                <div style="margin-left:200px;padding:0">Casier,</div>    
                            </div>
                        </td>
                    </tr>
                </table>
               </div>
          ';
        } else {
            $invoice .= '';
        }

        $dompdf = new DOMPDF();
        $dompdf->load_html($invoice);
        $dompdf->render();
        $dompdf->set_paper('A4');
        $dompdf->stream("Factura-" . $invoice_obj->getIncrementId() . ".pdf");
    }

    public function receiptAction() {
        $this->incrementReceiptNumber();
        $url = $this->getUrl(
                '*/sales_invoice/view', array(
            'invoice_id' => $this->getRequest()->getParam('invoice_id')
                ));
        $this->_redirectUrl($url);
    }

    protected function incrementReceiptNumber() {
        Mage::getConfig()->saveConfig('receipt/invoicesettings/nr_receipt', Mage::getStoreConfig('receipt/invoicesettings/nr_receipt') + 1);
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();

        return false;
    }

    protected function getWords($num, $sep = '') {
        $num = strval($num);
        if ($num == "0")
            return "zero";
        for ($i = 0; $i < strlen($num); ++$i)
            if (!is_numeric($num[$i]))
                return "[NaN]";
        if (strlen($num) > strlen("999999999999"))
            return "[huge]";
        $conv = "";
        $level = 0;
        $current = "";
        while (strlen($num) > 0) {
            if (strlen($num) > 3) {
                $current = substr($num, (strlen($num) - 3));
                $num = substr($num, 0, strlen($num) - 3);
            } else {
                $current = $num;
                $num = "";
            }
            $crt = ($current != "000") ? $this->convGroup($current, $level, $sep) : '';
            $conv = $crt . $conv;
            ++$level;
        }
        return $conv;
    }

    /**
     * Methoda de conversie a grupurilor de 3 cifre
     * */
    protected function convGroup($nr, $level, $sep = '') {
        $val = intval($nr);
        if ($val == 0)
            return "";
        $decun = $val % 100;
        $hndr = floor($val / 100);
        $levels = array(
            'single' => array('', 'mie', 'milion', 'miliard'),
            'many' => array('', 'mii', 'milioane', 'miliarde')
        );
        $sfx = $levels[($val == 1) ? 'single' : 'many'][$level];
        $digits = array(
            array("", "unu", "doi", "trei", "patru", "cinci", "sase", "sapte", "opt", "noua"),
            array("", "un", "doua", "trei", "patru", "cinci", "sase", "sapte", "opt", "noua"),
            array("", "o", "doua", "trei", "patru", "cinci", "sase", "sapte", "opt", "noua"),
            array("", "un", "doi", "trei", "patru", "cinci", "sai", "sapte", "opt", "noua"),
            array("", "un", "doua", "trei", "patru", "cinci", "sai", "sapte", "opt", "noua"),);
        $text = $digits[2][$hndr] . $sep . (($hndr == 1) ? 'suta' : (($hndr > 1) ? 'sute' : '')) . $sep;
        if ($decun == 0)
            return $text . $sep . $sfx . $sep;
        if ($decun < 10)
            return $text . $digits[(($level == 0) ? 0 : (($level == 1) ? ($hndr > 0 ? 0 : 2) : 3))][$decun] . $sep . $sfx . $sep;
        if ($decun == 10)
            return $text . 'zece' . $sep . $sfx . $sep;
        if ($decun < 20)
            return $text . $digits[3][$decun % 10] . 'sprezece' . $sep . $sfx . $sep;
        return $text . $digits[4][$decun / 10] . 'zeci' . $sep . (($decun % 10) == '0' ? '' : ('si' . $sep . $digits[0][$decun % 10] . $sep)) . (($level > 0) ? ('de' . $sep) : '') . $sfx . $sep;
    }

}
