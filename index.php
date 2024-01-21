<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
  header('location:login.php');
}

function displayStars($rating) {
  $fullStars = floor($rating);
  $remainingStars = $rating - $fullStars;

  // Affiche les étoiles pleines
  for ($i = 0; $i < $fullStars; $i++) {
    echo '<i class="fa fa-star filled-star"></i>';
  }

  // Affiche la demi-étoile si nécessaire
  if ($remainingStars >= 0.5) {
    echo '<i class="fa fa-star-half-alt half-filled-star"></i>';
  }

  // Affiche les étoiles vides
  for ($i = ceil($rating); $i < 5; $i++) {
    echo '<i class="fa fa-star empty-star"></i>';
  }
}

if (isset($_POST['reserve'])) {
  $bookId = $_POST['book_select'];
  $startDate = $_POST['reserve_start_date'];
  $endDate = $_POST['reserve_end_date'];
  $userId = $_SESSION['user_id'];

  // Vérifier s'il existe déjà une réservation pour le même livre et la même période
  $checkQuery = "SELECT * FROM reservations 
                 WHERE book_id = '$bookId' 
                 AND (('$startDate' BETWEEN start_date AND end_date) OR ('$endDate' BETWEEN start_date AND end_date))";
  $checkResult = mysqli_query($conn, $checkQuery);

  if (mysqli_num_rows($checkResult) > 0) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom:0px!important;">
    The selected book is already reserved for the specified period.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>';
  } else {
    // Effectuer l'insertion si aucune réservation existante n'a été trouvée
    $insertQuery = "INSERT INTO reservations (user_id, book_id, start_date, end_date, date_creation)
                    VALUES ('$userId', '$bookId', '$startDate', '$endDate', NOW())";

    $result = mysqli_query($conn, $insertQuery);

    if ($result) {  
      echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom:0px!important;">
      Reservation saved successfully!
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>';
    }
  }
}
function getReservations()
{
  global $conn;
  $reservations = array();

  $selectQuery = "SELECT reservations.id, books.name AS book_name,books.id AS book_id, reservations.start_date, reservations.end_date
                  FROM reservations
                  JOIN books ON reservations.book_id = books.id";

  $result = mysqli_query($conn, $selectQuery);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $reservation = array(
        'id' => $row['id'],
        'title' => $row['book_name'],
        'start' => $row['start_date'],
        'end' => $row['end_date'],
        'color' => '#dc2f2f',
        'textColor' => '#FFFFFF',
        'book_id' => $row['book_id'],
        'deleteButton' => true  // Add a delete button
      );

      array_push($reservations, $reservation);
    }
  }

  return $reservations;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Read More And Make Success</title>
  <meta name="title" content="Bookish - Read More And Make Success">
  <meta name="description"
    content="Read More And Make Success The Result Of Perfection. - Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ad harum quibusdam, assumenda quia explicabo.">
  <link rel="shortcut icon" href="" type="image/svg+xml">
  <link rel="stylesheet" href="./assets/css/style.css">
  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

  <!-- Include FullCalendar and Moment.js -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .fc-event-container .fc-content .fc-title {
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      font-size: 12px !important;
    }
    .filled-star {
  color: gold;
}

.half-filled-star {
  color: gold;
}

.empty-star {
  color: #ddd;
}
  </style>
</head>

<body>
  <header class="header" data-header>
    <div class="container">
      <a href="" class="logo">Reserve Your Book</a>
      <nav class="navbar" data-navbar>
        <ul class="navbar-list">
          <li class="navbar-item">
            <a href="#home" class="navbar-link" data-nav-link>Home</a>
          </li>
          <li class="navbar-item">
            <a href="#all_books" class="navbar-link" data-nav-link>All Books</a>
          </li>
          <li class="navbar-item">
            <a href="#reservation" class="navbar-link" data-nav-link>Reservation</a>
          </li>
          <li class="navbar-item">
            <a href="#contact" class="navbar-link" data-nav-link>Contact</a>
          </li>
          <li class="navbar-item">
            <a href="logout.php" class="navbar-link" data-nav-link>Logout</a>
          </li>
        </ul>
      </nav>
      <button class="nav-toggle-btn" aria-label="toggle menu" data-nav-toggler>
        <ion-icon name="menu-outline" aria-hidden="true" class="open"></ion-icon>
        <ion-icon name="close-outline" aria-hidden="true" class="close"></ion-icon>
      </button>

    </div>
  </header>
  <main>
    <article>
      <section class="section hero" id="home" aria-label="home">
        <div class="container">
          <div class="hero-content">
            <p class="section-subtitle">Let's Choose The Best Book</p>
            <h1 class="h1 hero-title">Read More And Make Success The Result Of Perfection.</h1>
            <p class="section-text">
              Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ad harum quibusdam, assumenda quia explicabo.
            </p>
          </div>
          <div class="hero-banner has-before">
            <img src="./assets/images/book1.png" width="431" height="596">
          </div>
        </div>
      </section>
      <section class="section all_books" id="all_books" aria-label="all_books">
        <div class="container">
          <h2 class="h2 section-title has-underline">
            All Books
            <span class="span has-before"></span>
          </h2>
          <ul class="grid-list">
            <?php
            $selectQuery = "SELECT * FROM books";
            $result = mysqli_query($conn, $selectQuery);
            if ($result) {
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <li>
                  <div class="books-card">
                    <figure class="card-banner img-holder" style="--width: 450; --height: 300; margin:0rem;">
                      <img src="admin/<?php echo $row['image_path']; ?>" width="450" height="300" loading="lazy"
                        alt="Nominated" class="img-cover">
                    </figure>
                    <div class="card-content">
                      <h3 class="h3 card-title">
                        <?php echo $row['name']; ?>
                      </h3>
                      <p class="card-text" style="font-size: 1.5rem;">
                        category :
                        <?php echo $row['category']; ?>
                      </p>
                      <p class="card-text" style="font-size: 1.5rem;">
                        Author :
                        <?php echo $row['author']; ?>
                      </p>
                      <a href="#" class="btn btn-primary btn-lg" data-toggle="modal" style="float: right;"
                        data-target="#bookModal<?php echo $row['id']; ?>">View Details</a>

                    </div>
                  </div>
                </li>
                <div class="modal fade" id="bookModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog"
                  aria-labelledby="bookModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="bookModalLabel<?php echo $row['id']; ?>">
                          <?php echo $row['name']; ?> Details
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <p style="font-size:1.5rem;">
                          <?php echo $row['description']; ?>
                        </p>
                        <p>
                          <?php displayStars($row['rating']); ?>
                        </p>
                        <!-- Ajoutez d'autres champs comme nécessaire -->
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
              }
            } else {
              echo "Error: " . $selectQuery . "<br>" . mysqli_error($conn);
            }
            ?>



          </ul>

        </div>
      </section>

      <section class="reservation" id="reservation" aria-label="reservation" style="margin-bottom:80px;">
        <div class="container">
          <h2 class="h2 section-title has-underline">
            Choose your preferred book from the available time.
            <span class="span has-before"></span>
          </h2>
          <div id="calendar"></div>
        </div>
      </section>

      <div class="modal fade" id="reserve_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalLabel">Choose Book</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">
                  <i class="fa fa-close"></i>
                </span>
              </button>
            </div>
            <form id="reservation_form" action="" method="post">
              <div class="modal-body">
                <div class="img-container">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="book_select">Select Book:</label>
                        <select name="book_select" id="book_select" class="form-control">
                          <?php
                          $selectQuery = "SELECT * FROM books";
                          $resultNoReserved = mysqli_query($conn, $selectQuery);
                          if ($resultNoReserved) {
                            mysqli_data_seek($resultNoReserved, 0);
                            while ($row = mysqli_fetch_assoc($resultNoReserved)) {
                              echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="row col-sm-12 mt-4">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="reserve_start_date">Reserving start</label>
                          <input type="date" name="reserve_start_date" id="reserve_start_date"
                            class="form-control onlydatepicker" placeholder="start date">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="reserve_end_date">Reserving end</label>
                          <input type="date" name="reserve_end_date" id="reserve_end_date" class="form-control"
                            placeholder="end date">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <input type="submit" name="reserve" id="save" class="btn btn-primary" value="Save Reserve"
                    onclick="saveReservation()" />
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <section class="section contact" id="contact" aria-label="contact">
        <div class="container">
          <p class="section-subtitle">Contact</p>

          <h2 class="h2 section-title has-underline">
            Write me anything
            <span class="span has-before"></span>
          </h2>

          <div class="wrapper">

            <form action="" class="contact-form">

              <input type="text" name="name" placeholder="Your Name" required class="input-field">

              <input type="email" name="email_address" placeholder="Your Email" required class="input-field">

              <input type="text" name="subject" placeholder="Subject" required class="input-field">

              <textarea name="message" placeholder="Your Message" class="input-field"></textarea>

              <button type="submit" class="btn btn-primary">Send Now</button>

            </form>

            <ul class="contact-card">

              <li>
                <p class="card-title">Address:</p>

                <address class="address">
                  16, Lankaway <br>
                  Florida, USA 99544
                </address>
              </li>

              <li>
                <p class="card-title">Phone:</p>

                <a href="tel:1234567890" class="card-link">123 456 7890</a>
              </li>

              <li>
                <p class="card-title">Email:</p>

                <a href="mailto:support@support.com" class="card-link">support@support.com</a>
              </li>

              <li>
                <p class="social-list-title h3">Connect book socials</p>

                <ul class="social-list">

                  <li>
                    <a href="#" class="social-link">
                      <ion-icon name="logo-facebook"></ion-icon>
                    </a>
                  </li>

                  <li>
                    <a href="#" class="social-link">
                      <ion-icon name="logo-twitter"></ion-icon>
                    </a>
                  </li>

                  <li>
                    <a href="#" class="social-link">
                      <ion-icon name="logo-linkedin"></ion-icon>
                    </a>
                  </li>

                  <li>
                    <a href="#" class="social-link">
                      <ion-icon name="logo-youtube"></ion-icon>
                    </a>
                  </li>

                  <li>
                    <a href="#" class="social-link">
                      <ion-icon name="logo-whatsapp"></ion-icon>
                    </a>
                  </li>

                </ul>
              </li>

            </ul>

          </div>

        </div>
      </section>
    </article>
  </main>
  <footer class="footer">
    <div class="container">

      <div class="footer-top">
        <a href="" class="logo">Reserve Your Book</a>
        <ul class="footer-list">
          <li>
            <a href="#" class="footer-link">Home</a>
          </li>
          <li>
            <a href="#" class="footer-link">All Books</a>
          </li>
          <li>
            <a href="#" class="footer-link">Reservations</a>
          </li>
          <li>
            <a href="#" class="footer-link">Contact</a>
          </li>

        </ul>

      </div>

      <div class="footer-bottom">
        <p class="copyright">
          &copy; 2022 All right reserved. Made with ❤ by codewithsadee.
        </p>
      </div>

    </div>
  </footer>
  <script src="./assets/js/script.js" defer></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script>
    $(document).ready(function () {
      displayCalendar();
      $('#calendar').on('click', '.fa-trash', function () {
        var reservationId = $(this).data('reservation-id');
        // Display a confirmation dialog
        var confirmed = confirm('Are you sure you want to delete this reservation?');

        if (confirmed) {
          // User clicked OK, proceed with deletion
          deleteReservation(reservationId);
          location.reload();
        }
      });
    });
    function showEventEntryModal() {
      $('#reserve_entry_modal').modal('show');
    }

    function closeModal() {
      $('#reserve_entry_modal').modal('hide');
    }
    function deleteReservation(reservationId) {
      // Make an AJAX request to delete the reservation
      $.ajax({
        url: 'delete_reservation.php?action=delete&reservation_id=' + reservationId,
        type: 'GET',
        success: function (response) {
          console.log(response);
          closeModal();
          $('#calendar').fullCalendar('refetchEvents');
        },
        error: function (error) {
          // Handle error
          console.error(error);
        }
      });
    }

    function displayCalendar() {
      var calendar = $('#calendar').fullCalendar({
        defaultView: 'month',
        timeZone: 'local',
        editable: true,
        selectable: true,
        selectHelper: true,
        overlap: true,
        events: <?php echo json_encode(getReservations()); ?>,
        select: function (start, end) {
          $('#reserve_start_date').val(moment(start).format('YYYY-MM-DD'));
          $('#reserve_end_date').val(moment(end).format('YYYY-MM-DD'));
          showEventEntryModal();
        },
        eventRender: function (event, element) {
          if (event.deleteButton) {
            element.find('.fc-title').append('<i class="fa fa-trash" data-reservation-id="' + event.id + '"></i>');
          }
        }
      });
    }

  </script>
</body>

</html>