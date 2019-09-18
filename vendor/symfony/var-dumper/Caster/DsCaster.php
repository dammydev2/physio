rn true;
    }

    return false;
}

function highlight(root, activeNode, nodes) {
    resetHighlightedNodes(root);

    Array.from(nodes||[]).forEach(function (node) {
        if (!/\bsf-dump-highlight\b/.test(node.className)) {
            node.className = node.className + ' sf-dump-highlight';
        }
    });

    if (!/\bsf-dump-highlight-active\b/.test(activeNode.className)) {
        activeNode.className = activeNode.className + ' sf-dump-highlight-active';
    }
}

function resetHighlightedNodes(root) {
    Array.from(root.querySelectorAll('.sf-dump-str, .sf-dump-key, .sf-dump-public, .sf-dump-protected, .sf-dump-private')).forEach(function (strNode) {
        strNode.className = strNode.className.replace(/\bsf-dump-highlight\b/, '');
        strNode.className = strNode.className.replace(/\bsf-dump-highlight-active\b/, '');
    });
}

return function (root, x) {
    root = doc.getElementById(root);

    var indentRx = new RegExp('^('+(root.getAttribute('data-indent-pad') || '  ').replace(rxEsc, '\\$1')+')+', 'm'),
        options = {$options},
        elt = root.getElementsByTagName('A'),
        len = elt.length,
        i = 0, s, h,
        t = [];

    while (i < len) t.push(elt[i++]);

    for (i in x) {
        options[i] = x[i];
    }

    function a(e, f) {
        addEventListener(root, e, function (e) {
            if ('A' == e.target.tagName) {
                f(e.target, e);
            } else if ('A' == e.target.parentNode.tagName) {
                f(e.target.parentNode, e);
            } else if (e.target.nextElementSibling && '