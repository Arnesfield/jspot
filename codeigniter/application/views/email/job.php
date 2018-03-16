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
    <h3><?=$msg?>
      <span style="display: none"><?=date('l, d F Y H:i:s')?></span>
    </h3>
    <p>Dear <?=$fname . ' ' . $lname?>,
      <span style="display: none"><?=date('l, d F Y H:i:s')?></span>
    </p>
    <p>
      <?=$body?> for the job, <strong><?=$title?></strong>.
      <span style="display: none"><?=date('l, d F Y H:i:s')?></span>
    </p>
    <?php if (isset($date) && isset($time)): ?>
      <p>
        Your interview schedule is on <strong><?=date('l, d F Y', $date)?></strong> at <strong><?=$time?></strong>.
        <span style="display: none"><?=date('l, d F Y H:i:s')?></span>
      </p>
    <?php endif ?>
    <a
      style="color: #ff7043"
      href="<?=base_url('../#/profile/' . $id)?>"
    >View your profile
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
