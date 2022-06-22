
def makeDailyPage(events: List[LogEvent]):
    """
    This page shows all events for a single day
    """
    templatePath = pathlib.Path(__file__).parent.joinpath("template-body.html")
    with open(templatePath) as f:
        templateBody = f.read()

    html = """
    <table class='table'>
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Time</th>
                <th scope="col">IP</th>
                <th scope="col">URL</th>
                <th scope="col">Referrer</th>
                <th scope="col">Agent</th>
            </tr>
        </thead>
        <tbody>
    """

    for i, event in enumerate(events):
        if "Googlebot" in event.agent:
            trClass = "table-secondary"
        else:
            trClass = ""

        agentShort = event.agent.split(")")[0] + ")"

        html += f"""
            <tr class='{trClass}'>
                <th scope="row">{i}</th>
                <td>{event.timestamp}</td>
                <td>{event.ip}</td>
                <td class="breakable"><a href='{event.url}'>{event.url}</a></td>
                <td class="breakable"><a href='{event.ref}'>{event.ref}</a></td>
                <td><abbr title='{event.agent}'>{agentShort}</abbr></td>
            </tr>
        """

    html += "</tbody></table>"

    html = templateBody\
        .replace("{{TITLE}}", "Analytics Report")\
        .replace("{{CONTENT}}", html)

    outPath = pathlib.Path(__file__).parent.joinpath("out.html")
    with open(outPath, 'w') as f:
        f.write(html)
