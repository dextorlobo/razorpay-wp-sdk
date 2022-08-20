<?php
/**
 * Template class.
 *
 * @package razorpay-wp-sdk
 * @since 0.1.0
 */

declare( strict_types = 1 );

namespace Imarun\RazorpayWpSdk\Invoice;

/**
 * The core plugin class.
 *
 * @since   0.1.0
 * @package razorpay-wp-sdk
 */
class Template {

	private $template_data;

	private $template_html;

	public function __construct( $template_data ) {
		$this->template_data = $template_data;
	}

	/**
	 * Set template data.
	 */
	public function set_invoice_template_data() {
		$this->template_html = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tbody>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td width="49%">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tbody>
								<tr>
									<td>
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;"><b>Payment Receipt</b></td>
												</tr>
												<tr>
													<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">Receipt Number: '.$this->template_data->get_payment_id().'</td>
												</tr>
												<tr>
													<td>&nbsp;</td>
												</tr>
												<tr>
													<td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;"><b>Service Provider</b></td>
												</tr>
												<tr>
													<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">Sannidhya Baweja</td>
												</tr>
												<tr>
													<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">https://sannidhyabaweja.com/</td>
												</tr>
												<tr>
													<td>&nbsp;</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td width="51%" valign="top">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tbody>
								<tr>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:15px;" align="right">Receipt Date : '.$this->template_data->get_order_date().'</td>
								</tr>
								<tr>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" align="right">&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;" align="right"><b>Payer</b></td>
								</tr>
								<tr>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" align="right">'.$this->template_data->get_fname().' '.$this->template_data->get_lname().'</td>
								</tr>
								<tr>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" align="right">'.$this->template_data->get_email().'</td>
								</tr>
								<tr>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" align="right">'.$this->template_data->get_contact().'</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tbody>
								<tr>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:13px; border-top:1px solid #333; border-bottom:1px solid #333; border-left:1px solid #333; border-right:1px solid #333;" width="75%" height="32" align="center"><b>Description</b></td>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:13px; border-top:1px solid #333; border-bottom:1px solid #333; border-right:1px solid #333;" width="25%" align="center"><b>Amount in Rupee</b></td>
								</tr>
								<tr>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-left:1px solid #333; border-right:1px solid #333;" height="32" align="center">Product</td>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-right:1px solid #333;" align="center">'.$this->template_data->get_actual_amount().'</td>
								</tr>
								<tr>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-left:1px solid #333; border-right:1px solid #333;" height="32" align="center">CGST (2.5%)</td>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-right:1px solid #333;" align="center">'.$this->template_data->get_cgst_amount().'</td>
								</tr>
								<tr>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-left:1px solid #333; border-right:1px solid #333;" height="32" align="center">SGST (2.5%)</td>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-right:1px solid #333;" align="center">'.$this->template_data->get_sgst_amount().'</td>
								</tr>
								<tr>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-left:1px solid #333; border-right:1px solid #333;" height="32" align="center"><b>Total</b></td>
									<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-right:1px solid #333;" align="center">'.$this->template_data->get_amount().'</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" colspan="2"><b>Total Amount in Words:</b> '.$this->template_data->get_amount_in_words().'</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" colspan="2" align="center">(This is computer generated receipt and does not require physical signature.) <br>  Order ID : '.$this->template_data->get_payment_id().'</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
			</tbody>
		</table>';
	}

	/**
	 * Get invoice html template.
	 */
	public function get_invoice_html_template() {
		return $this->template_html;
	}
}
