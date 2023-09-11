<pre>
<?php

print_r($_POST);
?>


<form method="post" id="fingerprint" action="index.php">
<input type="text" id="visitor" name="visitorId" value="" hidden>
</form>

<script>
      // Initialize the agent at application startup.
  const fpPromise = import('https://openfpcdn.io/fingerprintjs/v3')
    .then(FingerprintJS => FingerprintJS.load())

  // Get the visitor identifier when you need it.
  fpPromise
    .then(fp => fp.get())
    .then(result => {
      // This is the visitor identifier:
      const visitorId = result.visitorId
      return visitorId;

}
)
console.log(visitorId)
    </script>