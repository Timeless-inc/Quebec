{{-- Keep Bootstrap in the base layer so Tailwind utilities retain precedence. --}}
<style>
    @layer theme, base, components, utilities;
    @import url('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css') layer(base);
</style>
