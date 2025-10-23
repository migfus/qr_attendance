Router
router.get(
$route('dashboard.arta-id.index'),
{ page, search: params.value.search, start: params.value.start, end: params.value.end, search_filter: params.value.search_filter.value },
defaultRouterState([])
)
