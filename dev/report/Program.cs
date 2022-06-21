namespace Report;

public static class Program
{
    public static void Main(string[] args)
    {
        //string repoRoot = Path.GetFullPath("../../../../../");
        string repoRoot = Path.GetFullPath("../../");
        string logFilePath = Path.Combine(repoRoot, "htdocs/logs/2022-06-21.txt");
        Entry[] entries = File.ReadAllLines(logFilePath).Select(x => Entry.FromLogLine(x)).ToArray();
        var groups = entries.GroupBy(x => x.Ip, y => y);


        System.Text.StringBuilder sb = new();
        sb.AppendLine("<html>");
        sb.AppendLine("<style>");
        sb.AppendLine("table {white-space: nowrap;}");
        sb.AppendLine("</style>");
        sb.AppendLine("<body>");
        sb.AppendLine("<table>");
        foreach (var group in groups)
        {
            Console.WriteLine(group.Key);
            sb.AppendLine("<tr><td>&nbsp;</td></tr>");
            foreach (Entry entry in group)
            {
                Console.WriteLine(entry.Url);
                sb.AppendLine("<tr>");
                sb.AppendLine($"<td>{entry.DateTime}</td>");
                sb.AppendLine($"<td>{entry.Ip}</td>");
                sb.AppendLine($"<td>{entry.Url}</td>");
                sb.AppendLine($"<td>{entry.Referrer}</td>");
                sb.AppendLine($"<td>{entry.Agent}</td>");
                sb.AppendLine("</tr>");
            }
        }
        sb.AppendLine("</table>");
        sb.AppendLine("</body>");
        sb.AppendLine("</html>");
        File.WriteAllText("report.html", sb.ToString());
    }
}