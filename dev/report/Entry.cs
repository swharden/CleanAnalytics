namespace Report;

struct Entry
{
    public DateTime DateTime;
    public string Ip;
    public string Url;
    public string Referrer;
    public string Agent;

    public Entry(DateTime dt, string ip, string url, string referrer, string agent)
    {
        DateTime = dt;
        Ip = ip;
        Url = url;
        Referrer = referrer;
        Agent = agent;
    }

    public static Entry FromLogLine(string line)
    {
        string[] parts = line.Split(' ', 5);
        if (parts.Length != 5)
            throw new InvalidOperationException($"invalid log line: '{line}'");
        DateTime dt = DateTime.Parse(parts[0]);
        string ip = parts[1];
        string url = parts[2];
        string referrer = parts[3];
        string agent = parts[4];
        return new Entry(dt, ip, url, referrer, agent);
    }
}
