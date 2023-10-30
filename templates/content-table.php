<?php if($results): ?>
  <table class="table table-hover">
  <thead>
    <th>Case</th>
    <th>Death</th>
    <th>Type</th>
    <th>Establishment</th>
    <th>Location</th>
    <th>Sex</th>
    <th>Age Group</th>
    <th>Ethnic Origin</th>
    <th>Stage</th>
  </thead>
  <tbody>
    <?php foreach($results as $result): ?>
      <tr>
        <?php foreach($result as $r): ?>
          <td><?= htmlspecialchars(stripslashes(trim($r))); ?></td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
  </table>
<?php endif; ?>
