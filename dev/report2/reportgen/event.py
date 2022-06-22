from dataclasses import dataclass


@dataclass
class Event:
    timestamp: str
    ip: str
    url: str
    ref: str
    agent: str
