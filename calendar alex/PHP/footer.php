<footer class="footer">
    <p class="text-muted">&copy; My Calendar</p>
</footer>
<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('#cal_select').datetimepicker({
        viewMode: 'years',
        format: 'YYYY-MM'
    });

    $('.custom-picker').datetimepicker({
            format: 'YYYY/MM/DD HH:mm'
    });
});
</script>