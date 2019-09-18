
      background: rgba(255, 100, 100, .17);
    }

pre:not(.prettyprinted) {
  padding-left: 60px;
}

#plain-exception {
  display: none;
}

#copy-button {
  cursor: pointer;
  border: 0;
}

.clipboard {
  opacity: .8;
  background: none;

  color: rgba(255, 255, 255, 0.1);
  box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.1);

  border-radius: 3px;

  outline: none !important;
}

  .clipboard:hover {
    box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.3);
    color: rgba(255, 255, 255, 0.3);
  }

/* inspired by githubs kbd styles */
kbd {
  -moz-border-bottom-colors: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  background-color: #fcfcfc;
  border-color: #ccc #ccc #bbb;
  border-image: none;
  border-style: solid;
  border-width: 1px;
  color: #555;
  display: inline-block;
  font-size: 11px;
  line-height: 10px;
  padding: 3px 5px;
  vertical-align: middle;
}


/* == Media queries */

/* Expand the spacing in the details section */
@media (min-width: 1000px) {
  .details, .frame-code {
    padding: 20px 40px;
  }

  .details-container {
    left: 32%;
    width: 68%;
  }

  .frames-container {
    margin: 5px;
  }

  .left-panel {
    width: 32%;
  }
}

/* Stack panels */
@media (max-width: 600px) {
  .panel {
    position: static;
    width: 100%;
  }
}

/* Stack details tables */
@media (max-width: 400px) {
  .data-table,
  .data-table tbody,
  .data-table tbody tr,
  .data-table tbody td {
    display: block;
    width: 100%;
  }

    .data-table tbody tr:first-child {
      padding-top: 0;
    }

      .data-table tbody td:first-child,
      .data-table tbody td:last-child {
        padding-left: 0;
        padding-right: 0;
      }

      .data-table tbody td:last-child {
        padding-top: 3px;
      }
}

.tooltipped {
  position: relative
}
.tooltipped:after {
  position: absolute;
  z-index: