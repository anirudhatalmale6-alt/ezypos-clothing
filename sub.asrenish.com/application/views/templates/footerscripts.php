<script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->

        <script src="<?php echo base_url().'assets/js/popper.min.js'?>"></script><!-- Tether for Bootstrap -->
        <script src="<?php echo base_url().'assets/js/bootstrap.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/js/waves.js'?>"></script>
        <script src="<?php echo base_url().'assets/js/jquery.nicescroll.js'?>"></script>
        <script src="<?php echo base_url().'assets/plugins/switchery/switchery.min.js'?>"></script>

                <!-- Required datatable js -->
        <script src="<?php echo base_url().'assets/plugins/datatables/jquery.dataTables.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/plugins/datatables/dataTables.bootstrap4.min.js'?>"></script>
        <!-- Buttons examples -->
        <script src="<?php echo base_url().'assets/plugins/datatables/dataTables.buttons.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/plugins/datatables/buttons.bootstrap4.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/plugins/datatables/jszip.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/plugins/datatables/pdfmake.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/plugins/datatables/vfs_fonts.js'?>"></script>
        <script src="<?php echo base_url().'assets/plugins/datatables/buttons.html5.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/plugins/datatables/buttons.print.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/plugins/datatables/buttons.colVis.min.js'?>"></script>
        <!-- Responsive examples -->
        <script src="<?php echo base_url().'assets/plugins/datatables/dataTables.responsive.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/plugins/datatables/responsive.bootstrap4.min.js'?>"></script>

        <!-- App js -->
        <script src="<?php echo base_url().'assets/js/jquery.core.js'?>"></script>
        <script src="<?php echo base_url().'assets/js/jquery.app.js'?>"></script>

        <!-- Sweetalert2 js -->
        <script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.all.js"></script>
        <script src="https://unpkg.com/promise-polyfill"></script>

        <!-- jquery ui date js -->
        <script src="<?php echo base_url().'assets/date/jquery-ui.js'?>"></script>


       <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->

        <script>
        /*
         * A number box must not change when the mouse wheel rolls over it.
         *
         * Every browser treats a focused <input type="number"> as a spinner and
         * turns a scroll into an increment. On a long form - a GRN, a sale, a
         * tailoring order - the user clicks a quantity, scrolls down to reach
         * the save button, and the quantity they just typed silently changes on
         * the way past. Nothing on screen says so.
         *
         * Blurring the box rather than swallowing the scroll is deliberate: the
         * value stops changing AND the page still scrolls. Cancelling the wheel
         * event instead would stop the page dead whenever the pointer happened
         * to be over a number field, which is its own kind of broken.
         *
         * Bound on the document, so it covers boxes added by script after the
         * page loaded - a sale line, an exchange row - not just the ones in the
         * original HTML. This is loaded on every page, so it applies everywhere.
         */
        (function () {
            function isNumberBox(el) {
                return el && el.tagName === 'INPUT' && String(el.type).toLowerCase() === 'number';
            }
            document.addEventListener('wheel', function (e) {
                var el = e.target;
                if (isNumberBox(el) && el === document.activeElement) {
                    el.blur();
                }
            }, { passive: true, capture: true });

            // The arrow keys are the same trap in miniature: a number box with
            // the cursor in it answers Up and Down by changing the figure. That
            // one is left alone - it is what the keys are for, and the user is
            // typing in the box at the time, not scrolling past it.
        })();
        </script>
    </body>

<!-- Mirrored from coderthemes.com/uplon/horizontal/pages-starter.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 08 Dec 2017 14:10:55 GMT -->
</html>