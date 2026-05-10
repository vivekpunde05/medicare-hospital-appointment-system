/**
 * validation.js
 * Shared client-side validation helpers for the MediCare Appointment System.
 * Loaded as a plain <script> tag — no imports/exports.
 */

/**
 * Checks whether a string is a valid email address.
 * Requires a non-empty local part, an '@' character, and a domain part
 * that contains at least one '.' with non-empty segments on both sides.
 *
 * @param {string} str - The string to validate.
 * @returns {boolean} true if valid, false otherwise.
 */
function isValidEmail(str) {
  if (typeof str !== 'string') return false;

  var atIndex = str.indexOf('@');
  if (atIndex <= 0) return false; // no '@' or empty local part

  var localPart = str.substring(0, atIndex);
  var domainPart = str.substring(atIndex + 1);

  if (localPart.length === 0) return false;
  if (domainPart.length === 0) return false;

  // Domain must contain at least one '.' with non-empty parts on both sides
  var dotIndex = domainPart.lastIndexOf('.');
  if (dotIndex <= 0) return false; // no dot, or dot is the first character
  if (dotIndex === domainPart.length - 1) return false; // dot is the last character

  return true;
}

/**
 * Checks whether a string represents a valid phone number.
 * Extracts only digit characters and returns false if the count is
 * fewer than 10 or more than 15.
 *
 * @param {string} str - The string to validate.
 * @returns {boolean} true if valid, false otherwise.
 */
function isValidPhone(str) {
  if (typeof str !== 'string') return false;

  var digits = str.replace(/\D/g, '');
  return digits.length >= 10 && digits.length <= 15;
}

/**
 * Checks whether a date string represents a strictly future date.
 * Compares the parsed date against today's date at midnight (local time).
 * Returns false if the date is today or in the past.
 *
 * @param {string} dateStr - A date string parseable by the Date constructor (e.g. "YYYY-MM-DD").
 * @returns {boolean} true if the date is strictly in the future, false otherwise.
 */
function isFutureDate(dateStr) {
  if (!dateStr) return false;

  var inputDate = new Date(dateStr);
  if (isNaN(inputDate.getTime())) return false;

  // Normalise both dates to midnight local time for a pure date comparison
  var today = new Date();
  today.setHours(0, 0, 0, 0);

  var input = new Date(inputDate.getFullYear(), inputDate.getMonth(), inputDate.getDate());

  return input > today;
}

/**
 * Shows an inline error message for a form field.
 * Looks for an element with id `fieldId + '-error'`, sets its text, and makes it visible.
 *
 * @param {string} fieldId - The id of the form field (without '-error' suffix).
 * @param {string} message - The error message to display.
 */
function showError(fieldId, message) {
  var errorEl = document.getElementById(fieldId + '-error');
  if (errorEl) {
    errorEl.textContent = message;
    errorEl.style.display = 'block';
  }
}

/**
 * Clears all inline error messages on the page.
 * Finds every element with class 'field-error' and hides it.
 */
function clearErrors() {
  var errorEls = document.querySelectorAll('.field-error');
  for (var i = 0; i < errorEls.length; i++) {
    errorEls[i].textContent = '';
    errorEls[i].style.display = 'none';
  }
}

/**
 * Validates the appointment booking form before submission.
 * Checks all required fields for emptiness/whitespace, then runs format
 * checks on email, phone, and date using the shared helper functions.
 * Displays inline error messages for each failing field.
 *
 * @returns {boolean} true if all fields are valid, false if any validation fails.
 */
function validateBookingForm() {
  clearErrors();

  var hasError = false;

  // --- Name ---
  var nameEl = document.getElementById('booking-name');
  var name = nameEl ? nameEl.value : '';
  if (!name.trim()) {
    showError('booking-name', 'Full name is required.');
    hasError = true;
  }

  // --- Email ---
  var emailEl = document.getElementById('booking-email');
  var email = emailEl ? emailEl.value : '';
  if (!email.trim()) {
    showError('booking-email', 'Email address is required.');
    hasError = true;
  } else if (!isValidEmail(email.trim())) {
    showError('booking-email', 'Please enter a valid email address.');
    hasError = true;
  }

  // --- Phone ---
  var phoneEl = document.getElementById('booking-phone');
  var phone = phoneEl ? phoneEl.value : '';
  if (!phone.trim()) {
    showError('booking-phone', 'Phone number is required.');
    hasError = true;
  } else if (!isValidPhone(phone.trim())) {
    showError('booking-phone', 'Phone number must contain 10–15 digits.');
    hasError = true;
  }

  // --- Doctor ---
  var doctorEl = document.getElementById('booking-doctor');
  var doctor = doctorEl ? doctorEl.value : '';
  if (!doctor.trim()) {
    showError('booking-doctor', 'Please select a doctor.');
    hasError = true;
  }

  // --- Date ---
  var dateEl = document.getElementById('booking-date');
  var date = dateEl ? dateEl.value : '';
  if (!date.trim()) {
    showError('booking-date', 'Appointment date is required.');
    hasError = true;
  } else if (!isFutureDate(date.trim())) {
    showError('booking-date', 'Please select a future date for your appointment.');
    hasError = true;
  }

  // --- Time Slot ---
  var timeSlotEl = document.getElementById('booking-time-slot');
  var timeSlot = timeSlotEl ? timeSlotEl.value : '';
  if (!timeSlot.trim()) {
    showError('booking-time-slot', 'Please select a time slot.');
    hasError = true;
  }

  return !hasError;
}

/**
 * Validates the contact form before submission.
 * Checks that name, email, subject, and message are all non-empty/non-whitespace,
 * and that the email address is in a valid format.
 * Displays inline error messages for each failing field.
 *
 * @returns {boolean} true if all fields are valid, false if any validation fails.
 */
function validateContactForm() {
  clearErrors();

  var hasError = false;

  // --- Name ---
  var nameEl = document.getElementById('contact-name');
  var name = nameEl ? nameEl.value : '';
  if (!name.trim()) {
    showError('contact-name', 'Name is required.');
    hasError = true;
  }

  // --- Email ---
  var emailEl = document.getElementById('contact-email');
  var email = emailEl ? emailEl.value : '';
  if (!email.trim()) {
    showError('contact-email', 'Email address is required.');
    hasError = true;
  } else if (!isValidEmail(email.trim())) {
    showError('contact-email', 'Please enter a valid email address.');
    hasError = true;
  }

  // --- Subject ---
  var subjectEl = document.getElementById('contact-subject');
  var subject = subjectEl ? subjectEl.value : '';
  if (!subject.trim()) {
    showError('contact-subject', 'Subject is required.');
    hasError = true;
  }

  // --- Message ---
  var messageEl = document.getElementById('contact-message');
  var message = messageEl ? messageEl.value : '';
  if (!message.trim()) {
    showError('contact-message', 'Message is required.');
    hasError = true;
  }

  return !hasError;
}
