<table>

  <thead>
    <h1 style="color: #1e88e5;">
      <img
        style="vertical-align: middle; display: inline-block; width: 32px; height: 32px"
        data-time="<?=date('l, d F Y H:i:s')?>"
        src="<?=base_url('../static/images/logo.png')?>"/>
      <span style="vertical-align: middle;">JSpot
        <span style="display: none"><?=date('l, d F Y H:i:s')?></span>
      </span>
      <span style="display: none"><?=date('l, d F Y H:i:s')?></span>
    </h1>
  </thead>

  <tbody>
    <h3>Email Verification
      <span style="display: none"><?=date('l, d F Y H:i:s')?></span>
    </h3>
    <p>Dear <?=$fname . ' ' . $lname?>,
      <span style="display: none"><?=date('l, d F Y H:i:s')?></span>
    </p>
    <p>
      We need you to confirm this email address in order to get started with exploring with JSpot. Email confirmation is simple and fast, just click on the link below to complete this process.
      <span style="display: none"><?=date('l, d F Y H:i:s')?></span>
    </p>
    <a
      style="color: #ff7043"
      href="<?=base_url('signup/verify/' . $code)?>"
    >Verify email address
      <span style="display: none"><?=date('l, d F Y H:i:s')?></span>
    </a>
  </tbody>
  
  <tfoot>
    <div style="padding-top: 24px;">
      <small>
        <p style="color: #757575">
          Email sent to <span style="color: #ff7043; text-decoration: underline"><?=$email?></span>
          at <strong><?=date('l, d F Y H:i:s')?></strong>.
          <br><br>
          ITWSPEC7 Final Project.
          <br>
          FEUTECH &copy; 2017-2018. JSpot Web and Mobile Application.
          <span style="display: none"><?=date('l, d F Y H:i:s')?></span>
        </p>
      </small>
    </div>
  </tfoot>

</table>
