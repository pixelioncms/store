<?php

$assetsUrl = Yii::app()->assetManager->publish(
    Yii::getPathOfAlias('mod.admin.views.admin.mailtpl.test'), false, -1, YII_DEBUG
);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<title>Email Template</title>
	

	<style type="text/css" media="screen">
		/* Linked Styles */
		body { padding:0 !important; margin:0 !important; display:block !important; -webkit-text-size-adjust:none; background:.background-body }
		a { color:#e27251; text-decoration:none }
		h2 a { color:#1a1a1a; text-decoration:none }

		/* Campaign Monitor wraps the text in editor in paragraphs. In order to preserve design spacing we remove the padding/margin */
		p { padding:0 !important; margin:0 !important } 
	</style>
</head>
<body class="body" style="padding:0 !important; margin:0 !important; display:block !important; -webkit-text-size-adjust:none; background:.background-body">

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td align="center" valign="top">
			<!-- Top -->
			<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#050506">
				<tr>
					<td align="center">
						<table width="620" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"><div style="font-size:0pt; line-height:0pt; height:30px"><img src="<?=$this->assetsUrl;?>/images/empty.gif" width="1" height="30" style="height:30px" alt="" /></div>
</td>
											<td class="top" style="color:#797a7a; font-family:Georgia; font-size:11px; line-height:15px; text-align:left">
												Having trouble viewing this email? <webversion class="link-top" style="color:#ff6000; text-decoration:underline">View it in your browser</webversion>
											</td>
											<td align="right">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="12"><img src="<?=$this->assetsUrl;?>/images/bullet1.jpg" alt="" border="0" width="7" height="5" /></td>
														<td class="top" style="color:#797a7a; font-family:Georgia; font-size:11px; line-height:15px; text-align:left"><preferences class="link-top" style="color:#ff6000; text-decoration:underline">Update preferences</preferences></td>
														<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="11"></td>
														<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="12"><img src="<?=$this->assetsUrl;?>/images/bullet1.jpg" alt="" border="0" width="7" height="5" /></td>
														<td class="top" style="color:#797a7a; font-family:Georgia; font-size:11px; line-height:15px; text-align:left"><forwardtoafriend class="link-top" style="color:#ff6000; text-decoration:underline">Forward to a friend</forwardtoafriend></td>
													</tr>
												</table>
											</td>
											<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<div style="font-size:0pt; line-height:0pt; height:1px; background:#3d3f40; "><img src="<?=$assetsUrl;?>/images/empty.gif" width="1" height="1" style="height:1px" alt="" /></div>

			<!-- Header -->
			<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#27292a">
				<tr>
					<td align="center">
						<table width="620" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
								<td>
									<div style="font-size:0pt; line-height:0pt; height:25px"><img src="<?= Yii::app()->createAbsoluteUrl($assetsUrl.'/images/empty.gif');?>" width="1" height="25" style="height:25px" alt="" /></div>

									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?= Yii::app()->createAbsoluteUrl($this->assetsUrl.'/images/logob.png');?>" alt="" border="0" width="265" height="52" /></td>
											<td class="issue" style="color:#ffffff; font-family:Georgia; font-size:16px; line-height:20px; text-align:right; font-style:italic">
												<singleline>Issue 22</singleline>
												<div class="date" style="color:#ff6000; font-family:Georgia; font-size:22px; line-height:26px; text-align:right; font-style:normal"><currentmonthname> <currentday></div>
											</td>
										</tr>
									</table>
									<div style="font-size:0pt; line-height:0pt; height:25px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="25" style="height:25px" alt="" /></div>

								</td>
								<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!-- END Header -->
			<!-- Hero -->
			<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f7f3ed">
				<tr>
					<td align="center">
						<table width="620" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<repeater>
										<table width="100%" border="0" cellspacing="0" cellpadding="0" >
											<tr>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
												<td>
													<div style="font-size:0pt; line-height:0pt; height:20px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="20" style="height:20px" alt="" /></div>


													<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#d2cfca">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?= Yii::app()->createAbsoluteUrl($assetsUrl.'/images/hero.jpg');?>" alt="" border="0" width="578" height="269" /></td>
														</tr>
													</table>
													<div style="font-size:0pt; line-height:0pt; height:15px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="15" style="height:15px" alt="" /></div>

													<div class="hero-text" style="color:#656363; font-family:Georgia; font-size:18px; line-height:25px; text-align:left; font-style:normal">
														<multiline>Lorem ipsum dolor sit amet, consectetur adipiscing elit in sed dolor ipsum turpis, at condimentum eros. </multiline>
													</div>
													<div style="font-size:0pt; line-height:0pt; height:20px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="20" style="height:20px" alt="" /></div>

												</td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
											</tr>
										</table>
									</repeater>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!-- END Hero -->
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top">
						<div style="font-size:0pt; line-height:0pt; height:23px; background:#f7f3ed; "><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="23" style="height:23px" alt="" /></div>

					</td>
					<td align="center" width="580">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td valign="top" width="300">
									<div style="font-size:0pt; line-height:0pt; height:23px; background:#f7f3ed; "><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="23" style="height:23px" alt="" /></div>

								</td>
								<td class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/find_out_more.jpg" editable="true" alt="" border="0" width="280" height="36" /></td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<div style="font-size:0pt; line-height:0pt; height:23px; background:#f7f3ed; "><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="23" style="height:23px" alt="" /></div>

					</td>
				</tr>
			</table>
			<div style="font-size:0pt; line-height:0pt; height:20px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="20" style="height:20px" alt="" /></div>

			<table width="620" border="0" cellspacing="0" cellpadding="0">
				<!-- Content -->
				<tr>
					<td>
						<repeater>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
									<td>
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top" width="270">
													<div class="h2" style="color:#1a1a1a; font-family:Georgia; font-size:14px; line-height:18px; text-align:left; font-weight:normal">
														<singleline>Heading Title Goes Here</singleline>
													</div>
													<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>

													<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#cfcfcf">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/img1.jpg" editable="true" alt="" border="0" width="278" height="119" /></td>
														</tr>
													</table>
													<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>


													<div class="text" style="color:#1f2122; font-family:Georgia; font-size:12px; line-height:20px; text-align:left">
														<multiline>
															Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.
														</multiline>
														<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>


														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="12"><img src="<?=$assetsUrl?>/images/bullet2.jpg" alt="" border="0" width="8" height="7" /></td>
																<td class="text" style="color:#1f2122; font-family:Georgia; font-size:12px; line-height:20px; text-align:left"><multiline><a href="#" target="_blank" class="link" style="color:#e27251; text-decoration:none"><span class="link" style="color:#e27251; text-decoration:none">Find Out More</span></a></multiline></td>
															</tr>
														</table>
														<div style="font-size:0pt; line-height:0pt; height:15px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="15" style="height:15px" alt="" /></div>

														<div class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/separator1.jpg" alt="" border="0" width="279" height="1" /></div>
														<div style="font-size:0pt; line-height:0pt; height:15px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="15" style="height:15px" alt="" /></div>

													</div>

												</td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
												<td valign="top" width="270">
													<div class="h2" style="color:#1a1a1a; font-family:Georgia; font-size:14px; line-height:18px; text-align:left; font-weight:normal">
														<singleline>Heading Title Goes Here</singleline>
													</div>
													<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>

													<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#cfcfcf">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/img2.jpg" editable="true" alt="" border="0" width="278" height="119" /></td>
														</tr>
													</table>
													<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>


													<div class="text" style="color:#1f2122; font-family:Georgia; font-size:12px; line-height:20px; text-align:left">
														<multiline>
															Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.
														</multiline>
														<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>


														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="12"><img src="<?=$assetsUrl?>/images/bullet2.jpg" alt="" border="0" width="8" height="7" /></td>
																<td class="text" style="color:#1f2122; font-family:Georgia; font-size:12px; line-height:20px; text-align:left"><multiline><a href="#" target="_blank" class="link" style="color:#e27251; text-decoration:none"><span class="link" style="color:#e27251; text-decoration:none">Find Out More</span></a></multiline></td>
															</tr>
														</table>
														<div style="font-size:0pt; line-height:0pt; height:15px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="15" style="height:15px" alt="" /></div>

														<div class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/separator1.jpg" alt="" border="0" width="279" height="1" /></div>
														<div style="font-size:0pt; line-height:0pt; height:15px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="15" style="height:15px" alt="" /></div>

													</div>

												</td>
											</tr>
										</table>
									</td>
									<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
								</tr>
							</table>
						</repeater>
						<repeater>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
									<td>
										<div class="h2" style="color:#1a1a1a; font-family:Georgia; font-size:14px; line-height:18px; text-align:left; font-weight:normal">
											<singleline>Take a Look at Our Gallery</singleline>
										</div>
										<div style="font-size:0pt; line-height:0pt; height:13px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="13" style="height:13px" alt="" /></div>


										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top" width="120">
													<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#c1c1c1">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/img3.jpg" editable="true" alt="" border="0" width="118" height="83" /></td>
														</tr>
													</table>
													<div style="font-size:0pt; line-height:0pt; height:7px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="7" style="height:7px" alt="" /></div>

													<div class="h3" style="color:#656363; font-family:Georgia; font-size:12px; line-height:16px; text-align:left; font-weight:normal">
														<singleline>Title 1 Goes Here</singleline>
													</div>
												</td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="33"></td>
												<td valign="top" width="120">
													<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#c1c1c1">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/img4.jpg" editable="true" alt="" border="0" width="118" height="83" /></td>
														</tr>
													</table>
													<div style="font-size:0pt; line-height:0pt; height:7px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="7" style="height:7px" alt="" /></div>

													<div class="h3" style="color:#656363; font-family:Georgia; font-size:12px; line-height:16px; text-align:left; font-weight:normal">
														<singleline>Title 2 Goes Here</singleline>
													</div>
												</td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="33"></td>
												<td valign="top" width="120">
													<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#c1c1c1">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/img5.jpg" editable="true" alt="" border="0" width="118" height="83" /></td>
														</tr>
													</table>
													<div style="font-size:0pt; line-height:0pt; height:7px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="7" style="height:7px" alt="" /></div>

													<div class="h3" style="color:#656363; font-family:Georgia; font-size:12px; line-height:16px; text-align:left; font-weight:normal">
														<singleline>Title 3 Goes Here</singleline>
													</div>
												</td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="33"></td>
												<td valign="top" width="120">
													<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#c1c1c1">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/img6.jpg" editable="true" alt="" border="0" width="118" height="83" /></td>
														</tr>
													</table>
													<div style="font-size:0pt; line-height:0pt; height:7px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="7" style="height:7px" alt="" /></div>

													<div class="h3" style="color:#656363; font-family:Georgia; font-size:12px; line-height:16px; text-align:left; font-weight:normal">
														<singleline>Title 4 Goes Here</singleline>
													</div>
												</td>
											</tr>
										</table>
										<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>

										<div class="img" style="font-size:0pt; line-height:0pt; text-align:left">
											<img src="<?=$assetsUrl?>/images/more_gallery_shots.jpg" editable="true" alt="" border="0" width="580" height="36" />
										</div>
									</td>
									<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
								</tr>
							</table>
							<div style="font-size:0pt; line-height:0pt; height:30px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="30" style="height:30px" alt="" /></div>

						</repeater>

						<repeater>
							<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f7f3ed">
								<tr>
									<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"><div style="font-size:0pt; line-height:0pt; height:50px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="50" style="height:50px" alt="" /></div>
<div style="font-size:0pt; line-height:0pt; height:4px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="4" style="height:4px" alt="" /></div>
</td>
									<td class="section-title" style="color:#656363; font-family:Georgia; font-size:20px; line-height:24px; text-align:left; font-weight:bold"><singleline>Latest Products</singleline></td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
									<td>
										<div style="font-size:0pt; line-height:0pt; height:20px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="20" style="height:20px" alt="" /></div>


										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top" class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="204">
													<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#bdbdbd">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/img7.jpg" editable="true" alt="" border="0" width="202" height="119" /></td>
														</tr>
													</table>
												</td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="17"></td>
												<td class="text2" style="color:#656363; font-family:Georgia; font-size:12px; line-height:20px; text-align:left" valign="top">
													<div class="h2" style="color:#1a1a1a; font-family:Georgia; font-size:14px; line-height:18px; text-align:left; font-weight:normal">
														<singleline>Lorem ipsum dolor amet</singleline>
													</div>
													<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>

													<multiline>
														Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy s ut. Curabitur rutrum tellus venenatis lectus condimentum.
													</multiline>
													<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>


													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="12"><img src="<?=$assetsUrl?>/images/bullet2.jpg" alt="" border="0" width="8" height="7" /></td>
															<td class="text" style="color:#1f2122; font-family:Georgia; font-size:12px; line-height:20px; text-align:left"><multiline><a href="#" target="_blank" class="link" style="color:#e27251; text-decoration:none"><span class="link" style="color:#e27251; text-decoration:none">Find out More</span></a></multiline></td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
										<div style="font-size:0pt; line-height:0pt; height:20px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="20" style="height:20px" alt="" /></div>

										<div class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/separator2.jpg" alt="" border="0" width="580" height="1" /></div>
										<div style="font-size:0pt; line-height:0pt; height:20px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="20" style="height:20px" alt="" /></div>



										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top" class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="204">
													<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#bdbdbd">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/img8.jpg" editable="true" alt="" border="0" width="202" height="119" /></td>
														</tr>
													</table>
												</td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="17"></td>
												<td class="text2" style="color:#656363; font-family:Georgia; font-size:12px; line-height:20px; text-align:left" valign="top">
													<div class="h2" style="color:#1a1a1a; font-family:Georgia; font-size:14px; line-height:18px; text-align:left; font-weight:normal">
														<singleline>Lorem ipsum dolor amet</singleline>
													</div>
													<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>

													<multiline>
														Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy s ut. Curabitur rutrum tellus venenatis lectus condimentum.
													</multiline>
													<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>


													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="12"><img src="<?=$assetsUrl?>/images/bullet2.jpg" alt="" border="0" width="8" height="7" /></td>
															<td class="text" style="color:#1f2122; font-family:Georgia; font-size:12px; line-height:20px; text-align:left"><multiline><a href="#" target="_blank" class="link" style="color:#e27251; text-decoration:none"><span class="link" style="color:#e27251; text-decoration:none">Find out More</span></a></multiline></td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
										<div style="font-size:0pt; line-height:0pt; height:20px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="20" style="height:20px" alt="" /></div>

										<div class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/separator2.jpg" alt="" border="0" width="580" height="1" /></div>
										<div style="font-size:0pt; line-height:0pt; height:20px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="20" style="height:20px" alt="" /></div>



										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top" class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="204">
													<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#bdbdbd">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left"><img src="<?=$assetsUrl?>/images/img9.jpg" editable="true" alt="" border="0" width="202" height="119" /></td>
														</tr>
													</table>
												</td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="17"></td>
												<td class="text2" style="color:#656363; font-family:Georgia; font-size:12px; line-height:20px; text-align:left" valign="top">
													<div class="h2" style="color:#1a1a1a; font-family:Georgia; font-size:14px; line-height:18px; text-align:left; font-weight:normal">
														<singleline>Lorem ipsum dolor amet</singleline>
													</div>
													<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>

													<multiline>
														Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy s ut. Curabitur rutrum tellus venenatis lectus condimentum.
													</multiline>
													<div style="font-size:0pt; line-height:0pt; height:10px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="10" style="height:10px" alt="" /></div>


													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="12"><img src="<?=$assetsUrl?>/images/bullet2.jpg" alt="" border="0" width="8" height="7" /></td>
															<td class="text" style="color:#1f2122; font-family:Georgia; font-size:12px; line-height:20px; text-align:left"><multiline><a href="#" target="_blank" class="link" style="color:#e27251; text-decoration:none"><span class="link" style="color:#e27251; text-decoration:none">Find out More</span></a></multiline></td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
										<div style="font-size:0pt; line-height:0pt; height:40px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="40" style="height:40px" alt="" /></div>

									</td>
									<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
								</tr>
							</table>
						</repeater>
					</td>
				</tr>
				<!-- END Content -->
			</table>
			<div style="font-size:0pt; line-height:0pt; height:1px; background:#000000; "><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="1" style="height:1px" alt="" /></div>

			<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#1f2122">
				<tr>
					<td align="center">
						<table width="620" border="0" cellspacing="0" cellpadding="0" >
							<tr>
								<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
								<td>
									<div style="font-size:0pt; line-height:0pt; height:20px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="20" style="height:20px" alt="" /></div>

									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td valign="top" class="footer-left" style="color:#656363; font-family:Georgia; font-size:12px; line-height:16px; text-align:left">
												<div class="img" style="font-size:0pt; line-height:0pt; text-align:left">
													<img src="<?=$assetsUrl?>/images/footer_logo.jpg" editable="true" alt="" border="0" width="242" height="48" />
												</div>
												<div style="font-size:0pt; line-height:0pt; height:30px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="30" style="height:30px" alt="" /></div>

												<multiline>
													Copyright &copy; <currentyear> <span>Company Name</span>
												</multiline>
											</td>
											<td valign="top" class="footer-right" style="color:#656363; font-family:Georgia; font-size:12px; line-height:22px; text-align:right">
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td align="right">
															<table border="0" cellspacing="0" cellpadding="0">
																<tr>
																	<td class="img-right" style="font-size:0pt; line-height:0pt; text-align:right" width="30"><img src="<?=$assetsUrl?>/images/facebook.jpg" editable="true" alt="" border="0" width="26" height="26" /></td>
																	<td class="img-right" style="font-size:0pt; line-height:0pt; text-align:right" width="30"><img src="<?=$assetsUrl?>/images/behance.jpg" editable="true" alt="" border="0" width="26" height="26" /></td>
																	<td class="img-right" style="font-size:0pt; line-height:0pt; text-align:right" width="30"><img src="<?=$assetsUrl?>/images/twitter.jpg" editable="true" alt="" border="0" width="26" height="26" /></td>
																	<td class="img-right" style="font-size:0pt; line-height:0pt; text-align:right" width="30"><img src="<?=$assetsUrl?>/images/vimeo.jpg" editable="true" alt="" border="0" width="26" height="26" /></td>
																	<td class="img-right" style="font-size:0pt; line-height:0pt; text-align:right" width="30"><img src="<?=$assetsUrl?>/images/flickr.jpg" editable="true" alt="" border="0" width="26" height="26" /></td>
																	<td class="img-right" style="font-size:0pt; line-height:0pt; text-align:right" width="30"><img src="<?=$assetsUrl?>/images/rss.jpg" editable="true" alt="" border="0" width="26" height="26" /></td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												<div style="font-size:0pt; line-height:0pt; height:5px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="5" style="height:5px" alt="" /></div>

												<multiline>
													East Pixel Bld. 99, Creative City 9000, Republic of Design<br />
													<a href="http://www.YourSiteName.com" target="_blank" class="link" style="color:#e27251; text-decoration:none"><span class="link" style="color:#e27251; text-decoration:none">www.YourSiteName.com</span></a> | <a href="mailto:email@sitename.com" target="_blank" class="link" style="color:#e27251; text-decoration:none"><span class="link" style="color:#e27251; text-decoration:none">email@sitename.com</span></a><br />
													Phone: +1 (655) 606-605

												</multiline>
											</td>
										</tr>
									</table>
									<div style="font-size:0pt; line-height:0pt; height:15px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="15" style="height:15px" alt="" /></div>

								</td>
								<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!-- Bottom Content -->
			<div style="font-size:0pt; line-height:0pt; height:1px; background:#3d3f40; "><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="1" style="height:1px" alt="" /></div>

			<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#000000">
				<tr>
					<td align="center">
						<table width="620" border="0" cellspacing="0" cellpadding="0" >
							<tr>
								<td>
									<div style="font-size:0pt; line-height:0pt; height:15px"><img src="images/empty.gif" width="1" height="15" style="height:15px" alt="" /></div>

									<div class="footer" style="color:#808080; font-family:Georgia; font-size:11px; line-height:18px; text-align:center">
										You're receiving this newsletter because you bought templates from us.<br />
										Not interested anymore? <unsubscribe class="link3-u" style="color:#808080; text-decoration:underline">Unsubscribe Instantly</unsubscribe>.
									</div>
									<div style="font-size:0pt; line-height:0pt; height:15px"><img src="<?=$assetsUrl?>/images/empty.gif" width="1" height="15" style="height:15px" alt="" /></div>

								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!-- END Bottom Content -->
		</td>
	</tr>
</table>

</body>
</html>
