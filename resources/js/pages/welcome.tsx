import AppLogoIcon from "@/components/app-logo-icon";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import type { SharedData } from "@/types";
import { Head, Link, usePage } from "@inertiajs/react";

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="Specsense - Intelligent Construction Document Analysis">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link
                    href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
                    rel="stylesheet"
                />
            </Head>
            <div className="min-h-screen bg-gradient-to-b from-background to-muted/30 px-4 dark:from-background dark:to-background/80">
                {/* Navigation */}
                <header className="container mx-auto py-4">
                    <nav className="flex items-center justify-between">
                        <div className="flex items-center gap-2">
                            <div className="flex h-10 w-10 items-center justify-center rounded-md bg-primary">
                                <AppLogoIcon className="h-6 w-6 text-primary-foreground" />
                            </div>
                            <span className="font-bold text-xl">Specsense</span>
                        </div>
                        <div className="flex items-center gap-4">
                            {auth.user ? (
                                <Button asChild variant="default">
                                    <Link href={route("documents.index")}>
                                        Dashboard
                                    </Link>
                                </Button>
                            ) : (
                                <>
                                    <Button asChild variant="ghost">
                                        <Link href={route("login")}>
                                            Log in
                                        </Link>
                                    </Button>
                                    <Button asChild>
                                        <Link href={route("register")}>
                                            Register
                                        </Link>
                                    </Button>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                {/* Hero Section */}
                <section className="container mx-auto py-16 md:py-24">
                    <div className="grid gap-12 md:grid-cols-2 md:gap-16 lg:gap-24">
                        <div className="flex flex-col justify-center space-y-6">
                            <div>
                                <Badge
                                    className="mb-4 px-3 py-1 text-sm"
                                    variant="secondary"
                                >
                                    Intelligent Analysis
                                </Badge>
                                <h1 className="font-bold text-4xl tracking-tight sm:text-5xl md:text-6xl">
                                    Analyze Construction Documents Faster
                                </h1>
                                <p className="mt-6 text-lg text-muted-foreground md:text-xl">
                                    Specsense intelligently analyzes special
                                    provisions in construction documents,
                                    helping contractors make informed decisions
                                    on bidding jobs faster than ever.
                                </p>
                            </div>
                            <div className="flex flex-col gap-4 sm:flex-row">
                                <Button size="lg" className="gap-2">
                                    Get Started
                                    <svg
                                        width="16"
                                        height="16"
                                        viewBox="0 0 16 16"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <title>Arrow Right</title>
                                        <path
                                            d="M6.66675 3.33325L10.6667 7.99992L6.66675 12.6666"
                                            stroke="currentColor"
                                            strokeWidth="2"
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                        />
                                    </svg>
                                </Button>
                                <Button size="lg" variant="outline">
                                    Learn More
                                </Button>
                            </div>
                        </div>
                        <div className="relative flex items-center justify-center rounded-lg bg-muted/50 p-8 dark:bg-muted/20">
                            <div className="relative z-10 rounded-lg border bg-background/80 p-6 shadow-lg backdrop-blur-sm">
                                <div className="space-y-2">
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center gap-2">
                                            <div className="h-3 w-3 rounded-full bg-green-500" />
                                            <span className="font-medium text-sm">
                                                Document Analysis
                                            </span>
                                        </div>
                                        <Badge variant="outline">
                                            In Progress
                                        </Badge>
                                    </div>
                                    <div className="h-2 w-full rounded-full bg-muted">
                                        <div className="h-full w-2/3 rounded-full bg-primary" />
                                    </div>
                                </div>
                                <div className="mt-6 space-y-4">
                                    <div className="flex items-center gap-3 rounded-md border p-3">
                                        <div className="flex h-8 w-8 items-center justify-center rounded bg-primary/10 text-primary">
                                            <svg
                                                width="16"
                                                height="16"
                                                viewBox="0 0 16 16"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <title>Checkmark</title>
                                                <path
                                                    d="M13.3334 4L6.00008 11.3333L2.66675 8"
                                                    stroke="currentColor"
                                                    strokeWidth="2"
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                />
                                            </svg>
                                        </div>
                                        <div>
                                            <p className="font-medium text-sm">
                                                Special Provision Identified
                                            </p>
                                            <p className="text-muted-foreground text-xs">
                                                Section 3.2.1 - Material
                                                Requirements
                                            </p>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-3 rounded-md border p-3">
                                        <div className="flex h-8 w-8 items-center justify-center rounded bg-primary/10 text-primary">
                                            <svg
                                                width="16"
                                                height="16"
                                                viewBox="0 0 16 16"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <title>Checkmark</title>
                                                <path
                                                    d="M13.3334 4L6.00008 11.3333L2.66675 8"
                                                    stroke="currentColor"
                                                    strokeWidth="2"
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                />
                                            </svg>
                                        </div>
                                        <div>
                                            <p className="font-medium text-sm">
                                                Cost Impact Calculated
                                            </p>
                                            <p className="text-muted-foreground text-xs">
                                                Estimated +12% to base bid
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Features Section */}
                <section className="container mx-auto py-16 md:py-24">
                    <div className="text-center">
                        <h2 className="font-bold text-3xl tracking-tight sm:text-4xl">
                            How Specsense Works
                        </h2>
                        <p className="mt-4 text-lg text-muted-foreground">
                            Our intelligent system analyzes construction
                            documents to identify critical information.
                        </p>
                    </div>
                    <div className="mt-16 grid gap-8 md:grid-cols-3">
                        <Card>
                            <CardHeader>
                                <div className="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                                    <svg
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        className="text-primary"
                                    >
                                        <title>Document Icon</title>
                                        <path
                                            d="M9 12H15M9 16H15M9 8H15M5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21Z"
                                            stroke="currentColor"
                                            strokeWidth="2"
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                        />
                                    </svg>
                                </div>
                                <CardTitle>Document Upload</CardTitle>
                                <CardDescription>
                                    Upload your construction documents and
                                    special provisions.
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <p className="text-muted-foreground text-sm">
                                    Our system accepts various document formats
                                    including PDF, DOC, and DOCX files.
                                </p>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader>
                                <div className="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                                    <svg
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        className="text-primary"
                                    >
                                        <title>Search Icon</title>
                                        <path
                                            d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                            stroke="currentColor"
                                            strokeWidth="2"
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                        />
                                    </svg>
                                </div>
                                <CardTitle>Intelligent Analysis</CardTitle>
                                <CardDescription>
                                    Our AI identifies key provisions and
                                    requirements.
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <p className="text-muted-foreground text-sm">
                                    Specsense uses advanced algorithms to detect
                                    special provisions that may impact project
                                    costs and timelines.
                                </p>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader>
                                <div className="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                                    <svg
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        className="text-primary"
                                    >
                                        <title>Chart Icon</title>
                                        <path
                                            d="M9 19V13C9 11.8954 8.10457 11 7 11H5C3.89543 11 3 11.8954 3 13V19C3 20.1046 3.89543 21 5 21H7C8.10457 21 9 20.1046 9 19ZM9 19V9C9 7.89543 9.89543 7 11 7H13C14.1046 7 15 7.89543 15 9V19M9 19C9 20.1046 9.89543 21 11 21H13C14.1046 21 15 20.1046 15 19M15 19V5C15 3.89543 15.8954 3 17 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21H17C15.8954 21 15 20.1046 15 19Z"
                                            stroke="currentColor"
                                            strokeWidth="2"
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                        />
                                    </svg>
                                </div>
                                <CardTitle>Decision Support</CardTitle>
                                <CardDescription>
                                    Get actionable insights for better bidding
                                    decisions.
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <p className="text-muted-foreground text-sm">
                                    Receive detailed reports with cost
                                    implications and risk assessments to help
                                    you make informed bidding decisions.
                                </p>
                            </CardContent>
                        </Card>
                    </div>
                </section>

                {/* CTA Section */}
                <section className="container mx-auto py-16 md:py-24">
                    <div className="rounded-lg bg-primary/5 p-8 md:p-12 lg:p-16">
                        <div className="mx-auto max-w-2xl text-center">
                            <h2 className="font-bold text-3xl tracking-tight sm:text-4xl">
                                Ready to streamline your bidding process?
                            </h2>
                            <p className="mt-4 text-lg text-muted-foreground">
                                Join contractors who are saving time and making
                                more informed decisions with Specsense.
                            </p>
                            <div className="mt-8 flex flex-col justify-center gap-4 sm:flex-row">
                                <Button size="lg">Get Started Today</Button>
                                <Button size="lg" variant="outline">
                                    Schedule a Demo
                                </Button>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Footer */}
                <footer className="border-t bg-background/50 py-8">
                    <div className="container mx-auto">
                        <div className="flex flex-col items-center justify-between gap-4 md:flex-row">
                            <div className="flex items-center gap-2">
                                <div className="flex h-8 w-8 items-center justify-center rounded-md bg-primary">
                                    <AppLogoIcon className="h-5 w-5 text-primary-foreground" />
                                </div>
                                <span className="font-semibold text-sm">
                                    Specsense
                                </span>
                            </div>
                            <div className="flex items-center gap-6">
                                <a
                                    href="/about"
                                    className="text-muted-foreground text-sm hover:text-foreground"
                                >
                                    About
                                </a>
                                <a
                                    href="/features"
                                    className="text-muted-foreground text-sm hover:text-foreground"
                                >
                                    Features
                                </a>
                                <a
                                    href="/pricing"
                                    className="text-muted-foreground text-sm hover:text-foreground"
                                >
                                    Pricing
                                </a>
                                <a
                                    href="/contact"
                                    className="text-muted-foreground text-sm hover:text-foreground"
                                >
                                    Contact
                                </a>
                            </div>
                            <div className="text-muted-foreground text-sm">
                                © {new Date().getFullYear()} Specsense. All
                                rights reserved.
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}
